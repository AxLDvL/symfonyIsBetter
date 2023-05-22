<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CountryFixtures extends Fixture
{
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('fr_FR');

        // Création de pays de démonstration
        $this->createCountry('France', 'FR', $manager);
        $this->createCountry('Allemagne', 'DE', $manager);
        $this->createCountry('Royaume-Uni', 'UK', $manager);

        // Génération aléatoire de pays supplémentaires
        for ($i = 0; $i < 10; $i++) {
            $nom = $this->faker->country;
            $iso = strtoupper($this->faker->countryCode);
            $this->createCountry($nom, $iso, $manager);
        }

        $manager->flush();
    }

    private function createCountry(string $nom, ?string $iso, ObjectManager $manager): void
    {
        $country = new Country();
        $country->setNom($nom);
        $country->setIso($iso);
        $manager->persist($country);
    }
}
