<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpKernel\Attribute\AsController;
class RegistController extends AbstractController
{
 
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }
    private EntityManagerInterface $entityManager;
    #[Route('api/regist', name: 'app_regist',methods:['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Récupérez les données du formulaire
        $requestData = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($requestData['username']);
        $user->setPassword($requestData['password']); 
        
       
        $hashedPassword = $userPasswordHasher->hashPassword($user, "password");
        $user->setPassword($hashedPassword);
       
        // Enregistrez l'utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'Utilisateur Enregister!']);
    }

   
}








