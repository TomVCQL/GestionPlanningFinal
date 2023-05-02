<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Entity\Matiere;
use App\Entity\Periode;
use App\Repository\AdministrateurRepository;
use App\Entity\Cours;
use App\Repository\CoursRepository;
use App\Repository\IntervenantRepository;
use App\Repository\MatiereRepository;
use App\Repository\PeriodeRepository;
use Cassandra\Date;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdministrateurController extends AbstractController
{
    #[Route('/administrateur', name: 'administrateur_connexion')]
    public function ajouter(Request $request, AdministrateurRepository $administrateurRepository)
    {

        $form = $this->createFormBuilder()
            ->add("pseudo", TextType::class, ['label'=>"pseudo"])
            ->add('mdp', PasswordType::class, ['label'=>"mots de passe"])
            ->getForm();

        $form->handleRequest($request);
        $session = $request->getSession();
        if($form->isSubmitted() && $form->isValid())
        {
            $pseudoSend = $form->get('pseudo')->getData();
            $mdpSend = $form->get('mdp')->getData();

            $administrateur = $administrateurRepository->findOneBy(['pseudoAdmin'=>$pseudoSend]);

            if($administrateur != null)
            {
                $pseudoAlter = $administrateur->getPseudoAdmin();
                $mdpAlter = $administrateur->getMdpAdmin();

                if($pseudoAlter == $pseudoSend)
                {
                    if($mdpAlter == $mdpSend)
                    {
                        $nom = $administrateur->getNomAdmin();
                        $prenom = $administrateur->getPrenomAdmin();
                        $session->set("pseudo", $pseudoSend);
                        $session->set("prenom", $prenom);
                        $session->set("nom", $nom);
                        return $this->redirectToRoute("administrateur_accueil");
                    }
                    else {
                        $this->addFlash(
                            'aie',
                            'Mauvais mots de passe'
                        );
                        return $this->redirectToRoute('administrateur_connexion');
                    }
                }
                else
                {
                    $this->addFlash(
                        'aie',
                        'Mauvais pseudo'
                    );
                    return $this->redirectToRoute('administrateur_connexion');
                }
            }
            else
            {
                $this->addFlash(
                    'aie',
                    'Mauvais pseudo'
                );
                return $this->redirectToRoute('administrateur_connexion');

            }
        }
        $adminURL = $this->generateUrl("administrateur_connexion");
        $intervenantURL = $this->generateUrl("intervenant_connexion");
        $etudiantURL = $this->generateUrl("etudiant_connexion");
        return $this->render('/intervenant/index.html.twig', ['form'=> $form->createView(),'adminUrl'=>$adminURL,'intervenantUrl'=>$intervenantURL,'etudiantUrl'=>$etudiantURL
        ]);
    }

    /**
     * @Route("/administrateur/accueil", name="administrateur_accueil")
     */
    public function accueillir(Request $request, IntervenantRepository $intervenantRepository, MatiereRepository $matiereRepository, PeriodeRepository $periodeRepository)
    {
        $intervenants = $intervenantRepository->findAll();
        $matieres = $matiereRepository->findAll();
        $periodes = $periodeRepository->findAll();
        $intervenantArray = [];
        $matiereArray = [];

        foreach ($intervenants as $i) {
            $intervenantArray[] = [
                'id' => $i->getId(),
                'nom' => $i->getNomInter(),
                'prenom' =>$i->getPrenomInter(),
            ];
        }

        foreach ($matieres as $i) {
            $matiereArray[] = [
                'id' => $i->getId(),
                'nom' => $i->getNomMat(),
            ];
        }

        $events = [];

        foreach($periodes as $p)
        {
            $events[]=
                [
                    'title' => 'ENTREPRISE',
                    'id'=>$p->getId(),
                    'start' => $p->getDateDebutPeriode()->format('Y-m-d H:i:s'),
                    'end' => $p->getDateFinPeriode()->format('Y-m-d H:i:s'),
                    'color' => "red",
                ];
        }

        foreach ($intervenants as $intervenant){
            $matieres = $intervenant->getIdInter();
            foreach ($matieres as $matiere) {
                $cours = $matiere->getIdMat();
                foreach ($cours as $c) {
                    $events[] = [
                        'title' => $intervenant->getPrenomInter() . ' ' . $intervenant->getNomInter() . "\n" . $matiere->getNomMat(),
                        'start' => $c->getDebutC()->format('Y-m-d H:i:s'),
                        'end' => $c->getFinC()->format('Y-m-d H:i:s'),
                        'color' => "blue",
                        'id'=>$c->getId(),
                        'className' => ['eventLigne'],
                    ];
                }
            }
        }

        $session = $request->getSession();
        $nom = $session->get('nom');
        $prenom = $session->get('prenom');
        $pseudo = $session->get('pseudo');
        return $this->render('/administrateur/accueil.html.twig', ["pseudo"=>$pseudo, "nom"=>$nom, "prenom"=>$prenom, "intervenants"=>$intervenantArray, "matieres"=>$matiereArray, "events"=>$events]);
    }

    /**
     * @Route("/administrateur/create", name="administrateur_create")
     */
    public function create(Request $request, CoursRepository $coursRepository, MatiereRepository $matiereRepository, PeriodeRepository $periodeRepository):Response
    {
        $dateDebut = $request->request->get('dateDebut');
        $dateFin = $request->request->get('dateFin');
        $typePeriode = $request->request->get('type_periode');
        $idmatiere = $request->request->get('matiere');

        if($typePeriode == "cours")
        {
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $dateFin);

            $interval = $dateDebut->diff($dateFin);
            $duree = (new DateTime("00:00:00"))->add($interval);

            $cours = new Cours();
            $cours->setDebutC($dateDebut);
            $cours->setFinC($dateFin);
            $cours->setDureeC($duree);

            $matiere = $matiereRepository->findOneBy(['id'=>$idmatiere]);
            $matiere->addIdMat($cours);
            $coursRepository->save($cours,true);
        }
        else
        {

            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $dateFin);

            $periode = new Periode();
            $periode->setDateDebutPeriode($dateDebut);
            $periode->setDateFinPeriode($dateFin);
            $periode->setTypePeriode(2);

            $periodeRepository->save($periode, true);
        }



//        return $this->redirectToRoute('administrateur_accueil');
        return new Response("reussie");
    }

    /**
     * @Route("/administrateur/modify", name="administrateur_create")
     */
    public function modify(Request $request, CoursRepository $coursRepository, MatiereRepository $matiereRepository, PeriodeRepository $periodeRepository):Response
    {
        $dateDebut = $request->request->get('dateDebut');
        $dateFin = $request->request->get('dateFin');
        $typePeriode = $request->request->get('type_periode');
        $idmatiere = $request->request->get('matiere');
        $idPeriodeCours = $request->request->get('id');
        $periode = $periodeRepository->findOneBy(['id'=>$idPeriodeCours]);
        if($typePeriode == "cours")
        {
            if($cours = $coursRepository->findOneBy(['id'=>$idPeriodeCours]))
            {
                $matiere = $matiereRepository->findOneBy(['id'=>$idmatiere]);
                $matiere->removeIdMat($cours);
                $coursRepository->remove($cours,true);

                $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
                $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $dateFin);

                $interval = $dateDebut->diff($dateFin);
                $duree = (new DateTime("00:00:00"))->add($interval);


                $cours->setDebutC($dateDebut);
                $cours->setFinC($dateFin);
                $cours->setDureeC($duree);

                $matiere->addIdMat($cours);
            }
            else
            {

                $matiere = $matiereRepository->findOneBy(['id'=>$idmatiere]);
                $cours = new Cours();
                $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
                $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $dateFin);
                $interval = $dateDebut->diff($dateFin);
                $duree = (new DateTime("00:00:00"))->add($interval);
                $cours->setDebutC($dateDebut);
                $cours->setFinC($dateFin);
                $cours->setDureeC($duree);

                $matiere->addIdMat($cours);
                $periodeRepository->remove($periode);
            }
            $coursRepository->save($cours,true);
        }
        elseif($typePeriode == "Entreprise")
        {
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $dateDebut);
            $dateFin = DateTime::createFromFormat('Y-m-d H:i:s', $dateFin);


            if($periode = $periodeRepository->findOneBy(['id'=>$idPeriodeCours]))
            {
                $periode->setDateDebutPeriode($dateDebut);
                $periode->setDateFinPeriode($dateFin);
                $periode->setTypePeriode(2);

            }
            else
            {
                $periode = new Periode();
                $periode->setDateDebutPeriode($dateDebut);
                $periode->setDateFinPeriode($dateFin);
                $periode->setTypePeriode(2);

                $cours = $coursRepository->findOneBy(['id'=>$idPeriodeCours]);
                $matiere = $matiereRepository->findOneBy(['id'=>$idmatiere]);
                $matiere->removeIdMat($cours);
                $coursRepository->remove($cours,true);

            }
            $coursRepository->save($cours,true);
            $periodeRepository->save($periode, true);
        }
        elseif($typePeriode == "Supprimer")
        {
            if($cours = $coursRepository->findOneBy(['id'=>$idPeriodeCours]))
            {
                $matiere = $matiereRepository->findOneBy(['id'=>$idmatiere]);
                $matiere->removeIdMat($cours);
                $coursRepository->remove($cours,true);
            }
            elseif($periode = $periodeRepository->findOneBy(['id'=>$idPeriodeCours]))
            {
                $periodeRepository->remove($periode,true);
            }
        }

        return new Response("reussie");
    }

    /**
     * @Route("/administrateur/gestion", name="administrateur_gestion")
     */
    public function gestion(Request $request, MatiereRepository $matiereRepository, IntervenantRepository $intervenantRepository)
    {
        $matieres = $matiereRepository->findAll();
        $matiereArray = [];
        foreach($matieres as $mat)
        {
            $matiereArray["nom"] = $mat->getNomMat();
            $matiereArray["id"] = $mat->getId();
        }

        $formIntervenant = $this->createFormBuilder()
            ->add("Nom_intervenant", TextType::class, ['label'=>"Nom intervenant"])
            ->add('Prenom_intervenant', TextType::class, ['label'=>"Prenom intervenant"])
            ->add('Pseudo_intervenant', TextType::class, ['label'=>"Pseudo intervenant"])
            ->add('Mots_de_passe_intervenant', PasswordType::class, ['label'=>"Mots de passe intervenant"])
            ->getForm();

        $formIntervenant->handleRequest($request);

        $formMatiere = $this->createFormBuilder()
            ->add("Nom_matiere", TextType::class, ['label'=>"Nom matiere"])
            ->add('heure_total', NumberType::class, ['label'=>"heure total"])
            ->getForm();

        $formMatiere->handleRequest($request);

        if($formIntervenant->isSubmitted() && $formIntervenant->isValid())
        {
            $idMatiere = $_POST['matiere'];
            $matiere = $matiereRepository->findOneBy(['id'=>$idMatiere]);

            $intervenant =  new Intervenant();
            $intervenant->setNomInter($formIntervenant->get('Nom_intervenant')->getData());
            $intervenant->setPrenomInter($formIntervenant->get('Prenom_intervenant')->getData());
            $intervenant->setPseudoInter($formIntervenant->get('Pseudo_intervenant')->getData());
            $intervenant->setMdpInter($formIntervenant->get('Mots_de_passe_intervenant')->getData());
            $intervenant->setNbrHeureTravaillee(0);
            $intervenant->addIdInter($matiere);

            $intervenantRepository->save($intervenant, true);
            $this->addFlash('ok', 'Utilisateur créer');


            return $this->redirectToRoute('administrateur_gestion');
        }

        return $this->render("administrateur/gestion.html.twig",['formIntervenant'=>$formIntervenant->createView(),'matieres'=>$matieres]);
    }

    /**
     * @Route("/administrateur/creationMat", name="administrateur_creationMat")
     */
    public function creationMat(Request $request, MatiereRepository $matiereRepository, IntervenantRepository $intervenantRepository)
    {
        $formMatiere = $this->createFormBuilder()
            ->add("Nom_matiere", TextType::class, ['label'=>"Nom matiere"])
            ->add('heure_total', NumberType::class, ['label'=>"heure total"])
            ->getForm();

        $formMatiere->handleRequest($request);

        if($formMatiere->isSubmitted() && $formMatiere->isValid())
        {
            $matiere =  new Matiere();
            $matiere->setNomMat($formMatiere->get('Nom_matiere')->getData());
            $matiere->setHeureTotalMat($formMatiere->get('heure_total')->getData());
            $matiere->setHeureEnseigne(0);

            $matiereRepository->save($matiere, true);
            $this->addFlash('ok', 'Matiere créer avec succés');


            return $this->redirectToRoute('administrateur_creationMat');
        }
        return $this->render("administrateur/creation.html.twig",['formMatiere'=>$formMatiere->createView()]);
    }
}
