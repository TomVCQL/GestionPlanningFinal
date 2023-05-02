<?php

namespace App\Controller;

use App\Entity\Alternant;
use App\Repository\AlternantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\assertDoesNotMatchRegularExpression;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="accueil_form")
     */
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
                        return $this->redirectToRoute("alternant_accueil");
                    }
                    else {
                        $this->addFlash(
                            'aie',
                            'Mauvais mots de passe'
                        );
                        return $this->redirectToRoute('accueil_form');
                    }
                }
                else
                {
                    $this->addFlash(
                        'aie',
                        'Mauvais pseudo'
                    );
                    return $this->redirectToRoute('accueil_form');
                }
            }
            else
            {
                $this->addFlash(
                    'aie',
                    'Mauvais pseudo'
                );
                return $this->redirectToRoute('accueil_form');

            }
        }
        $adminURL = $this->generateUrl("administrateur_connexion");
        $intervenantURL = $this->generateUrl("intervenant_connexion");
        $etudiantURL = $this->generateUrl("etudiant_connexion");
        return $this->render('accueil.html.twig', ['form'=> $form->createView(),'adminUrl'=>$adminURL,'intervenantUrl'=>$intervenantURL,'etudiantUrl'=>$etudiantURL
        ]);
    }
}
