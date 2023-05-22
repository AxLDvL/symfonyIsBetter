<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/animal')]
class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository, SerializerInterface $serializer): JsonResponse
    {
        $animals = $animalRepository->findAll();

        return $this->json($animals, Response::HTTP_OK);
    }

    #[Route('/new', name: 'app_animal_new', methods: ['POST'])]
    public function new(Request $request, AnimalRepository $animalRepository, CountryRepository $countryRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        //echo $data['pays'];
        $country = $countryRepository->find($data['pays']);

        $animal = new Animal();

        $animal->setNom($data['nom']);
        $animal->setTailleMoyenne($data['tailleMoyenne']);
        $animal->setDureeDeVieMoyenne($data['dureeDeVieMoyenne']);
        $animal->setArtMartial($data['artMartial']);
        $animal->setNumeroTelephone($data['numeroTelephone']);
        $animal->setPays($country);


        // Persistez les changements dans la base de données
        $animalRepository->save($animal, true);

        return new JsonResponse([
                    'id' => $animal->getId(),
                    'nom' => $animal->getNom(),
                    'tailleMoyenne' => $animal->getTailleMoyenne(),
                    'dureeDeVieMoyenne' => $animal->getDureeDeVieMoyenne(),
                    'artMartial' => $animal->getArtMartial(),
                    'numeroTelephone' => $animal->getNumeroTelephone(),
                    'pays' => $animal->getPays()->getNom()

            ], Response::HTTP_CREATED);
        }

    #[Route('/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return new JsonResponse([
            'animal' => [
                'id' => $animal->getId(),
                'nom' => $animal->getNom(),
                'tailleMoyenne' => $animal->getTailleMoyenne(),
                'dureeDeVieMoyenne' => $animal->getDureeDeVieMoyenne(),
                'artMartial' => $animal->getArtMartial(),
                'numeroTelephone' => $animal->getNumeroTelephone(),
                'pays' => $animal->getPays()->getNom()
            ],
        ], Response::HTTP_OK);
    }

    #[Route('/{id}/edit', name: 'app_animal_edit', methods: ['PATCH'])]
    public function edit(Request $request, Animal $animal, AnimalRepository $animalRepository,CountryRepository $countryRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        // Vérifiez si chaque paramètre est défini avant de le mettre à jour
        if (isset($data['nom'])) {
            $animal->setNom($data['nom']);
        }
        if (isset($data['tailleMoyenne'])) {
            $animal->setTailleMoyenne($data['tailleMoyenne']);
        }
        if (isset($data['dureeDeVieMoyenne'])) {
            $animal->setDureeDeVieMoyenne($data['dureeDeVieMoyenne']);
        }
        if (isset($data['artMartial'])) {
            $animal->setArtMartial($data['artMartial']);
        }
        if (isset($data['numeroTelephone'])) {
            $animal->setNumeroTelephone($data['numeroTelephone']);
        }
        if (isset($data['pays'])) {
            //  $data['pays'] contient l'ID du pays
            // Vous pouvez récupérer l'objet Country à partir de l'ID
            $country = $countryRepository->find($data['pays']);
            $animal->setPays($country);
        }

        // Persistez les changements dans la base de données
        $animalRepository->save($animal, true);

        // Retournez une réponse JSON avec les données mises à jour de l'animal
        return new JsonResponse([
            'message' => 'Animal updated successfully',
            'animal' => [
                'id' => $animal->getId(),
                'nom' => $animal->getNom(),
                'tailleMoyenne' => $animal->getTailleMoyenne(),
                'dureeDeVieMoyenne' => $animal->getDureeDeVieMoyenne(),
                'artMartial' => $animal->getArtMartial(),
                'numeroTelephone' => $animal->getNumeroTelephone(),
                'pays' => $animal->getPays()->getNom()
            ],
        ], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_animal_delete', methods: ['DELETE'])]
    public function delete(int $id, AnimalRepository $animalRepository): Response
    {
        $animal = $animalRepository->find($id);
        $animalRepository->remove($animal, true);

        return new JsonResponse([
            'message' => 'Animal deleted successfully',
        ], Response::HTTP_OK);
      }
}
