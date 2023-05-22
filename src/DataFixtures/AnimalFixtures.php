<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnimalFixtures extends Fixture implements DependentFixtureInterface
{
    protected $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();


        //récupérer country
        $countries= $manager->getRepository(Country::class)->findAll();

        // Génération aléatoire d'animaux supplémentaires
        for ($i = 0; $i < 10; $i++) {
            $nom = $this->faker->firstName;
            $tailleMoyenne = $this->faker->numberBetween(10, 300);
            $pays = $this->faker->randomElement($countries);
            $pays = $pays->getNom();
            $dureeDeVieMoyenne = $this->faker->numberBetween(1, 50);
            $artMartial = $this->faker->word;
            $this->createAnimal($nom, $tailleMoyenne, $pays, $dureeDeVieMoyenne, $artMartial, $manager);
        }

        $manager->flush();
    }

    private function createAnimal(string $nom, ?int $tailleMoyenne, string $pays, ?int $dureeDeVieMoyenne, ?string $artMartial, ObjectManager $manager): void
    {
        $animal = new Animal();
        $animal->setNom($nom);
        $animal->setTailleMoyenne($tailleMoyenne);

        // Recherche du pays par son nom
        $countryRepository = $manager->getRepository(Country::class);
        $country = $countryRepository->findOneBy(['nom' => $pays]);
        $animal->setPays($country);

        $animal->setDureeDeVieMoyenne($dureeDeVieMoyenne);
        $animal->setArtMartial($artMartial);

        $manager->persist($animal);
    }

    public function getDependencies():array
    {
        return [
            CountryFixtures::class,
        ];
    }
}
