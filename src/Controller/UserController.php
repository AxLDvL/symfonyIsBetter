<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->json($users, Response::HTTP_OK);
    }

    #[Route('/sign-in', name: 'app_sign_in', methods: ['POST'])]
    public function signIn(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(['pseudo' => $data['pseudo']]);

        if (!$user || !$encoder->isPasswordValid($user, $data['motDePasse'])) {
            return $this->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $JWTManager->create($user);
        return $this->json(['token' => $token], Response::HTTP_OK);
    }
    #[Route('/sign-up', name: 'app_sign_up', methods: ['POST'])]
    public function signUp(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        $data= json_decode($request->getContent(), true);
        $user = new User;

        $user->setPseudo($data['pseudo']);
        $user->setMotDePasse($hasher->hashPassword($user, $data['motDePasse']));

        // Persistez les changements dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('User created successfully', Response::HTTP_CREATED);
    }
}
