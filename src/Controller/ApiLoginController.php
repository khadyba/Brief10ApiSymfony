<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiLoginController extends AbstractController
{
    private $entityManager;
    private $userPasswordHasher;
    protected $container;


    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher,  ContainerInterface  $container)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->container = $container;
    }

    #[Route('/api/login', name: 'api_login')]
    public function login(Request $request,JWTTokenManagerInterface $jwtManager)
    {
        $requestData = json_decode($request->getContent(), true);
        $email = $requestData['email'] ?? null;
        $password = $requestData['password'] ?? null;
        
        
        if (!$email || !$password) {
            return $this->json([
                'message' => 'Invalid request data',
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Accès à l'EntityManager directement via la propriété $this->entityManager
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        
        if (!$user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        // Vérification du mot de passe
        if (!$this->userPasswordHasher->hashPassword($user, $password)) {
            // dd($password);
            return $this->json([
                'message' => 'Identifiant incorrect',
            ], Response::HTTP_UNAUTHORIZED);
        }
    // Génération du token JWT
    $token = $jwtManager->create($user);
    // dd($user);

    // Vérification du rôle de l'utilisateur
    $roles = $user->getRoles();
    $message = '';

    if (in_array('ROLE_ADMIN', $roles, true)) {
        $message = 'Bienvenue sur votre espace administrateur!';
    } elseif (in_array('ROLE_USER', $roles, true)) {
        $message = 'Bienvenue dans la communauté Simplon!';
    }

    // Authentification réussie avec token et message de bienvenue
    return $this->json([
        'message' => 'Félicitations, vous êtes maintenant connecté!',
        'token' => $token,
        'welcome_message' => $message,
    ]);

}
}