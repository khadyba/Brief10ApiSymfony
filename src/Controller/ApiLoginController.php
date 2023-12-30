<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiLoginController extends AbstractController
{
    private $entityManager;
    private $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/api/login', name: 'api_login')]
    public function login(Request $request,JWTTokenManagerInterface $jwtManager)
    {
        $requestData = json_decode($request->getContent(), true);
        $username = $requestData['username'] ?? null;
        $password = $requestData['password'] ?? null;
        
        
        if (!$username || !$password) {
            return $this->json([
                'message' => 'Invalid request data',
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Accès à l'EntityManager directement via la propriété $this->entityManager
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        
        if (!$user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        // Vérification du mot de passe
        if (!$this->userPasswordHasher->hashPassword($user, $password)) {
            // dd($password);
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
    // Génération du token JWT
    $token = $jwtManager->create($user);

    // Authentification réussie avec token
    return $this->json([
        'message' => 'Félicitations Vous etes maintenant Connectez!',
        'token' => $token,
       
    ]);
        }

}
