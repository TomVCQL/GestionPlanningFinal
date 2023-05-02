<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandesController extends AbstractController
{

    /**
     * @Route("/commandes")
     */
    //Affiche les différents type de commande
    function displayCommande()
    {
        return $this->render("commande.html.twig",
            ["types"=>[0=>"soldées",1=>"en cours",2=>"archiver"]]
        );
    }

    /**
     * @Route("/commandes/recapitulatif")
     */
    //Affiche le récapitulatif de toutes les commandes
    function recapCommand()
    {
        return new Response("Voici la page du récapitulatif des commandes");
    }

    /**
     * @Route("/commandes/{type}")
     */
    //Affiche les commandes en fonction de son type
    function displayTypeCommand($type): Response
    {
        return new Response(sprintf("Voici la page des commandes : %s",$type));
    }
}