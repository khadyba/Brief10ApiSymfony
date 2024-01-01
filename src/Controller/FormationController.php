<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class FormationController extends AbstractController

{
        private $entityManager;

        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }

        #[Route('/api/formations_liste', name: 'app_formation')]
        public function index(): Response
        {
            $formationRepository = $this->entityManager->getRepository(Formation::class);
            $formations = $formationRepository->findBy(['is_delete' => false]);
            // Conversion en format JSON
            $formattedFormations = [];
            foreach ($formations as $formation) {
                $formattedFormations[] = [
                    'libeller' => $formation->getLibeller(),
                    'description' => $formation->getDescription(),
                    'durer_formations' => $formation->getDurerFormations(),
                ];
            }
            return $this->json($formattedFormations);
        }

        #[Route('api/formation_create',name:'app_formations')]  
        #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour publier une formation')]
        public function create(Request $request, EntityManagerInterface $entityManager, Security $authChecker,ValidatorInterface $validator,): Response
        {
          //   dd($authChecker);
          if (!$authChecker->isGranted("ROLE_ADMIN")) {
              throw $this->createAccessDeniedException('Accès refusé : vous devez être administrateur.');
          }
        $requestData = json_decode($request->getContent(), true);
        $newFormation = new Formation();
        $newFormation->setLibeller($requestData['libeller']);
        $newFormation->setDescription($requestData['description']); 
        $newFormation->setDurerFormations($requestData['durer_formations']); 
        $errors = $validator->validate($newFormation);
        $entityManager->persist($newFormation);
        $entityManager->flush();

        return $this->json([
            'message' => 'Formation créée avec succès',
            'formation' => $newFormation 
        ]);
      }
      #[Route('/api/formations_modif/{id}', name: 'app_formations')]
      #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour modifier une formation')]
      public function update(int $id, Request $request): Response
      {
          $formation = $this->entityManager->getRepository(Formation::class)->find($id);
  
          if (!$formation) {
              throw $this->createNotFoundException('Formation non trouvée pour cet ID');
          }
  
          $requestData = json_decode($request->getContent(), true);
  
          
          if (isset($requestData['libeller'])) {
              $formation->setLibeller($requestData['libeller']);
          }
          if (isset($requestData['description'])) {
              $formation->setDescription($requestData['description']);
          }
          if (isset($requestData['durer_formations'])) {
            $formation->setDurerFormations($requestData['durer_formations']);
        }
          $this->entityManager->flush();
          return new Response('Formation mise à jour avec succès');
        
      }
      #[Route('/api/formations_delete/{id}', name: 'app_formations')]
      #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour publier une formation')]
      public function delete(int $id): Response
      {
          // Récupérer la formation par son ID
          $formation = $this->entityManager->getRepository(Formation::class)->find($id);
  
          // Vérifier si la formation existe
          if (!$formation) {
              throw $this->createNotFoundException('Formation non trouvée ');
          }
  
          // Marquer la formation comme supprimée
          $formation->setIsDelete(true);
  
          // Enregistrer les modifications dans la base de données
          $this->entityManager->flush();
  
          // Répondre avec un message de succès ou un objet JSON vide
          return new Response('Formation supprimée avec succès!');
          
      }
}
