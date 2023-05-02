<?php

namespace App\Controller;

use App\Repository\AlternantRepository;
use App\Repository\CoursRepository;
use App\Repository\IntervenantRepository;
use App\Repository\MatiereRepository;
use App\Repository\PeriodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlternantController extends AbstractController
{
    #[Route('/alternant', name: 'etudiant_connexion')]
    public function ajouter(Request $request, AlternantRepository $alternantRepository)
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

            $alternant = $alternantRepository->findOneBy(['pseudoAlter'=>$pseudoSend]);

            if($alternant != null)
            {
                $pseudoAlter = $alternant->getPseudoAlter();
                $mdpAlter = $alternant->getMdpAlter();

                if($pseudoAlter == $pseudoSend)
                {
                    if($mdpAlter == $mdpSend)
                    {
                        $nom = $alternant->getNomAlter();
                        $prenom = $alternant->getPrenomAlter();
                        $session->set("pseudo", $pseudoSend);
                        $session->set("prenom", $prenom);
                        $session->set("nom", $nom);
                        return $this->redirectToRoute("etudiant_accueil");
                    }
                    else {
                        $this->addFlash(
                            'aie',
                            'Mauvais mots de passe'
                        );
                        return $this->redirectToRoute('etudiant_connexion');
                    }
                }
                else
                {
                    $this->addFlash(
                        'aie',
                        'Mauvais pseudo'
                    );
                    return $this->redirectToRoute('etudiant_connexion');
                }
            }
            else
            {
                $this->addFlash(
                    'aie',
                    'Mauvais pseudo'
                );
                return $this->redirectToRoute('etudiant_connexion');

            }
        }
        $adminURL = $this->generateUrl("administrateur_connexion");
        $intervenantURL = $this->generateUrl("intervenant_connexion");
        $etudiantURL = $this->generateUrl("etudiant_connexion");
        return $this->render('/alternant/index.html.twig', ['form'=> $form->createView(),'adminUrl'=>$adminURL,'intervenantUrl'=>$intervenantURL,'etudiantUrl'=>$etudiantURL
        ]);
    }
    /**
     * @Route("/alternant/accueil", name="etudiant_accueil")
     */
    public function accueillir(Request $request, CoursRepository $coursRepository, PeriodeRepository $periodeRepository, MatiereRepository $matiereRepository, IntervenantRepository $intervenantRepository)
    {
        $session = $request->getSession();
        $nom = $session->get('nom');
        $prenom = $session->get('prenom');
        $pseudo = $session->get('pseudo');

        $events = [];
        $intervenants = $intervenantRepository->findAll();
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
                        'className' => ['eventLigne'],
                    ];
                }
            }
        }
        $periodes = $periodeRepository->findAll();
        foreach ($periodes as $periode) {
            $events[] = [
                'title' => "ENTREPRISE",
                'start' => $periode->getDateDebutPeriode()->format('Y-m-d H:i:s'),
                'end' => $periode->getDateFinPeriode()->format('Y-m-d H:i:s'),
                'color' => "red",
                'className' => ['eventLigne'],
            ];
        }
        return $this->render('/alternant/accueil.html.twig', ["pseudo"=>$pseudo, "nom"=>$nom, "prenom"=>$prenom,"events"=>$events]);
    }
}
