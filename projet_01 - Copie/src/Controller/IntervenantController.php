<?php

namespace App\Controller;

use App\Entity\Periode;
use App\Repository\CoursRepository;
use App\Repository\IntervenantRepository;
use App\Repository\MatiereRepository;
use App\Entity\Cours;
use App\Repository\PeriodeRepository;
use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IntervenantController extends AbstractController
{
    #[Route('/intervenant', name: 'intervenant_connexion')]
    public function ajouter(Request $request, IntervenantRepository $intervenantRepository)
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

            $intervenant = $intervenantRepository->findOneBy(['pseudoInter'=>$pseudoSend]);

            if($intervenant != null)
            {
                $pseudoAlter = $intervenant->getPseudoInter();
                $mdpAlter = $intervenant->getMdpInter();

                if($pseudoAlter == $pseudoSend)
                {
                    if($mdpAlter == $mdpSend)
                    {
                        $nom = $intervenant->getNomInter();
                        $prenom = $intervenant->getPrenomInter();
                        $session->set("pseudo", $pseudoSend);
                        $session->set("prenom", $prenom);
                        $session->set("nom", $nom);
                        return $this->redirectToRoute("intervenant_accueil");
                    }
                    else {
                        $this->addFlash(
                            'aie',
                            'Mauvais mots de passe'
                        );
                        return $this->redirectToRoute('intervenant_connexion');
                    }
                }
                else
                {
                    $this->addFlash(
                        'aie',
                        'Mauvais pseudo'
                    );
                    return $this->redirectToRoute('intervenant_connexion');
                }
            }
            else
            {
                $this->addFlash(
                    'aie',
                    'Mauvais pseudo'
                );
                return $this->redirectToRoute('intervenant_connexion');

            }
        }
        $adminURL = $this->generateUrl("administrateur_connexion");
        $intervenantURL = $this->generateUrl("intervenant_connexion");
        $etudiantURL = $this->generateUrl("etudiant_connexion");
        return $this->render('/intervenant/index.html.twig', ['form'=> $form->createView(),'adminUrl'=>$adminURL,'intervenantUrl'=>$intervenantURL,'etudiantUrl'=>$etudiantURL
        ]);
    }

    /**
     * @Route("/intervenant/accueil", name="intervenant_accueil")
     */
    public function accueillir(Request $request, CoursRepository $coursRepository, MatiereRepository $matiereRepository, IntervenantRepository $intervenantRepository,PeriodeRepository $periodeRepository)
    {
        $session = $request->getSession();
        $nom = $session->get('nom');
        $prenom = $session->get('prenom');
        $pseudo = $session->get('pseudo');

        $intervenant = $intervenantRepository->findOneBy(['pseudoInter'=>$pseudo]);
        $matieres = $intervenant->getIdInter();
        $events = [];
        foreach ($matieres as $matiere) {
            $cours = $matiere->getIdMat();
            foreach ($cours as $c) {
                $events[] = [
                    'title' => $prenom . ' ' . $nom . "\n" . $matiere->getNomMat(),
                    'start' => $c->getDebutC()->format('Y-m-d H:i:s'),
                    'end' => $c->getFinC()->format('Y-m-d H:i:s'),
                    'color' => "blue",
                    'className' => ['eventLigne'],
                ];
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

        /*$cours = $coursRepository->findAll();
        $coursArray = [];
        foreach ($cours as $c) {
            $coursArray[] = [
                'title' => $c->getId(),
                'start' => $c->getDebutC()->format('Y-m-d H:i:s'),
                'end' => $c->getFinC()->format('Y-m-d H:i:s'),
                'color' => "blue",
            ];
        }*/
        /*$event = new Cours();
        $event->setDebutC(new \DateTime('2023-04-24T14:30:00'));
        $event->setFinC(new \DateTime('2023-04-24T18:30:00'));*/

        return $this->render('/intervenant/accueil.html.twig', ["pseudo"=>$pseudo, "nom"=>$nom, "prenom"=>$prenom,"events"=>$events]);
    }
}
