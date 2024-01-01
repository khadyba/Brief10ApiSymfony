<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Candidater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CandidaterController extends AbstractController
{
    private $entityManager;

        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }
    #[Route('api/enregistrer/{idFormation}', name: 'candidature_enregistrer', methods: ['POST'])]
    #[IsGranted("ROLE_USER")]
    public function enregistrerCandidature(Request $request, int $idFormation): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if ($user) {
            $entityManager = $this->entityManager;

            // Trouver la formation correspondant à l'ID donné
            $formation = $entityManager->getRepository(Formation::class)->find($idFormation);

            if ($formation) {
                $newCandidature = new Candidater();
                $newCandidature->setRelatedFormation($formation);
                $newCandidature->setRelatedEntity($user); // Assumer que vous avez une méthode setRelatedEntity

                // Enregistrement dans la base de données
                $entityManager->persist($newCandidature);
                $entityManager->flush();

                return $this->json(['message' => 'Candidature enregistrée avec succès']);
            }

            return $this->json(['message' => 'Formation non trouvée'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['message' => 'Aucun utilisateur connecté'], Response::HTTP_UNAUTHORIZED);
    }

    #[Route('api/lister', name: 'candidature_lister', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour effectuer cette opération')]
    public function listerCandidatures(): Response
    {
        $entityManager = $this->entityManager;

        // Récupérer toutes les candidatures
        $candidatures = $entityManager->getRepository(Candidater::class)->findAll();
    
        $candidaturesDetails = [];
        foreach ($candidatures as $candidature) {
            $candidatureDetails = [
                'id' => $candidature->getId(),
                'user_details' => [
                    'id' => $candidature->getRelatedEntity()->getId(),
                    'email'=>$candidature->getRelatedEntity()->getEmail()
                ],
                'formation_details' => [
                    'id' => $candidature->getRelatedFormation()->getId(),
                    'libeller'=>$candidature->getRelatedFormation()->getLibeller()
                ]
            ];
    
            $candidaturesDetails[] = $candidatureDetails;
        }
    
        return $this->json($candidaturesDetails);
    }

    #[Route('api/accepter/{id}', name: 'candidature_accepter', methods: ['PUT'])]
    #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour effectuer cette opération')]
    public function accepterCandidature(int $id): Response
    {
        $entityManager =  $this->entityManager;
    
        // Récupérer la candidature par son ID
        $candidature = $entityManager->getRepository(Candidater::class)->find($id);
    
        if (!$candidature) {
            return $this->json(['message' => 'Candidature non trouvée'], Response::HTTP_NOT_FOUND);
        }
    
        // Modifier le statut de la candidature
        $candidature->setStatus('accepté');
    
        // Sauvegarder les modifications
        $entityManager->flush();
    
        return $this->json(['message' => 'Candidature acceptée']);
    }

    #[Route('api/lister_acceptees', name: 'candidature_lister_acceptees', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants pour effectuer cette opération')]
    public function listerCandidaturesAcceptees(): Response
    {
        $entityManager = $this->entityManager;
    
        // Récupérer toutes les candidatures ayant le statut "accepté"
        $candidaturesAcceptees = $entityManager->getRepository(Candidater::class)->findBy(['status' => 'accepté']);
    
        $candidaturesDetails = [];
        foreach ($candidaturesAcceptees as $candidature) {
            $candidatureDetails = [
                'id' => $candidature->getId(),
                'status' => $candidature->getStatus(),
                'user_details' => [
                    'id' => $candidature->getRelatedEntity()->getId(),
                    'email'=>$candidature->getRelatedEntity()->getEmail()
                    
                ],
                'formation_details' => [
                    'id' => $candidature->getRelatedFormation()->getId(),
                    'libeller'=>$candidature->getRelatedFormation()->getLibeller()

                ]
            ];
    
            $candidaturesDetails[] = $candidatureDetails;
        }
    
        return $this->json($candidaturesDetails);
    }

    #[Route('api/lister_refusees', name: 'candidature_lister_refusees', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN", message: 'Vous n\'avez pas les droits suffisants effectuer cette opération')]
    public function listerCandidaturesRefusees(): Response
    {
        $entityManager = $this->entityManager;
    
        // Récupérer toutes les candidatures ayant le statut "accepté"
        $candidaturesAcceptees = $entityManager->getRepository(Candidater::class)->findBy(['status' => 'refusé']);
    
        $candidaturesDetails = [];
        foreach ($candidaturesAcceptees as $candidature) {
            $candidatureDetails = [
                'id' => $candidature->getId(),
                'status' => $candidature->getStatus(),
                'user_details' => [
                    'id' => $candidature->getRelatedEntity()->getId(),
                    'email'=>$candidature->getRelatedEntity()->getEmail()
                    
                ],
                'formation_details' => [
                    'id' => $candidature->getRelatedFormation()->getId(),
                    'libeller'=>$candidature->getRelatedFormation()->getLibeller()

                ]
            ];
    
            $candidaturesDetails[] = $candidatureDetails;
        }
    
        return $this->json($candidaturesDetails);
        
    }
}
