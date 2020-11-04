<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Voiture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $marque1 = new Marque();
        $marque1->setLibelle("Tozota");
        $manager->persist($marque1);

        $marque2 = new Marque();
        $marque2->setLibelle("Odel");
        $manager->persist($marque2);

        $modele1 = new Modele();
        $modele1->setLibelle("Discover")
                ->setImage("modele1.jpg")
                ->setPrixMoyen(15000)
                ->setMarque($marque1);
        $manager->persist($modele1);

        $modele2 = new Modele();
        $modele2->setLibelle("Family")
                ->setImage("modele2.jpg")
                ->setPrixMoyen(12000)
                ->setMarque($marque1);
        $manager->persist($modele2);

        $modele3 = new Modele();
        $modele3->setLibelle("Retro")
                ->setImage("modele3.jpg")
                ->setPrixMoyen(15000)
                ->setMarque($marque2);
        $manager->persist($modele3);

        $modele4 = new Modele();
        $modele4->setLibelle("Luxury")
                ->setImage("modele4.jpg")
                ->setPrixMoyen(17000)
                ->setMarque($marque2);
        $manager->persist($modele4);

        $modele5 = new Modele();
        $modele5->setLibelle("Future")
                ->setImage("modele5.jpg")
                ->setPrixMoyen(25000)
                ->setMarque($marque1);
        $manager->persist($modele5);

        $modele6 = new Modele();
        $modele6->setLibelle("Suv")
                ->setImage("modele6.jpg")
                ->setPrixMoyen(27000)
                ->setMarque($marque2);
        $manager->persist($modele6);

        $modeles = [$modele1, $modele2, $modele3, $modele4, $modele5, $modele6];

        $faker = \Faker\Factory::create('fr_FR');

        // Pour chaque modele, on va créer entre 3 et 5 voitures
        foreach($modeles as $modele ){
            $rand = rand(3,5);
            for($i=1; $i < $rand; $i++){
                $voiture = new Voiture();
                //Pour chaque immatriculation on utilise une regex issu de faker regexify() pour avoir des immatriculations
                // de type XX1234XX cf doc faker
                //[A-Z]{2} => on génère 2 lettres de A à Z, on génère 3 à 4 chiffre entre 0 et 9 [0-9]{3,4}
                $voiture->setImmatriculation($faker->regexify('[A-Z]{2}[0-9]{3,4}[A-Z]{2}'))
                        //On veut soit 3 ou 5 portes -> cf doc faker
                        ->setNbPortes($faker->randomElement($array = array (3,5)))
                        //On veut une date comprise entre 1950 et 2019 -> cf doc faker
                        ->setAnnee($faker->numberBetween($min = 1950, $max = 2019))
                        ->setModele($modele);
                $manager->persist($voiture);
            }
        }

        $manager->flush();
    }
}
