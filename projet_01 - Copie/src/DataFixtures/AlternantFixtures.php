<?php

namespace App\DataFixtures;

use App\Entity\Alternant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AlternantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $alternant = new Alternant();
        $alternant->setIdSpe(0);
        $alternant->setMailAlter("tom.vicquelin@limayrac.fr");
        $alternant->setMdpAlter("1234");
        $alternant->setNomAlter("vicquelin");
        $alternant->setPrenomAlter("tom");
        $alternant->setPseudoAlter("TVI");

        $manager->persist($alternant);

        $manager->flush();
    }
}
