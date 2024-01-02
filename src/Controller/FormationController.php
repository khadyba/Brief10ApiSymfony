<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FormationController extends AbstractController
{
        private $entityManager;

        protected $container;

        public function __construct(EntityManagerInterface $entityManager, ContainerInterface  $container)
        {
            $this->entityManager = $entityManager;
            $this->container = $container;
        }

        #[Route('/api/formations_liste', name: 'app_formations')]
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

        // #[Route('api/formation_create', name:'app_formation')]  
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
      public function update(Formation $id, Request $request): Response
      {
        //   $formation = $this->entityManager->getRepository(Formation::class)->find($id);
  
          if (!$id) {
              throw $this->createNotFoundException('Formation non trouvée pour cet ID');
          }
  
          $requestData = json_decode($request->getContent(), true);
  
          
          if (isset($requestData['libeller'])) {
              $id->setLibeller($requestData['libeller']);
          }
          if (isset($requestData['description'])) {
             $id->setDescription($requestData['description']);
          }
          if (isset($requestData['durer_formations'])) {
           $id->setDurerFormations($requestData['durer_formations']);
        }
          $this->entityManager->flush();
          return new Response('Formation mise à jour avec succès');
        
      }
      #[Route('/api/formations_delete/{id}', name: 'app_formations')]
      #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour publier une formation')]
      public function delete(Formation $id): Response
      {
          // Récupérer la formation par son ID
        //   $formation = $this->entityManager->getRepository(Formation::class)->find($id);
  
          // Vérifier si la formation existe
          if (!$id) {
              throw $this->createNotFoundException('Formation non trouvée ');
          }
  
          // Marquer la formation comme supprimée
          $id->setIsDelete(true);
  
          // Enregistrer les modifications dans la base de données
          $this->entityManager->flush();
  
          // Répondre avec un message de succès ou un objet JSON vide
          return new Response('Formation supprimée avec succès!');
          
      }
}
