<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[AsController]
class RegistController extends AbstractController

{
      protected $container;
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface  $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }
    private EntityManagerInterface $entityManager;
    #[Route('api/regist', name: 'app_regist',methods:['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Récupérez les données du formulaire
        $requestData = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($requestData['email']);
        $user->setPassword($requestData['password']); 

        // // Assignation du rôle administrateur
        // $user->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $userPasswordHasher->hashPassword($user, "password");
        $user->setPassword($hashedPassword);
       // Enregistrement de l'utilisateur en base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        // // Enregistrez l'utilisateur dans la base de données
        // $this->entityManager->persist($user);
        // $this->entityManager->flush();

        return $this->json(['message' => 'Félicitations vous etes maintenant Inscris !']);
    }

   
}








