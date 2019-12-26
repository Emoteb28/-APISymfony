<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // on créé 10 personnes
        for ($i = 0; $i < 100; $i++) {
            $author = new Author();
            $author->setName($faker->name);
            $author->setLastname($faker->lastName);

            /*            $author->setAdresse($faker->streetAddress);
                        $personne->setVille($faker->city);
                        $personne->setCodePostal($faker->postcode);
                        $personne->setDescription($faker->text);
                        $personne->setEmail($faker->email);
                        $manager->persist($personne);*/


            $manager->persist($author);
        }
        $manager->flush();
    }
}
