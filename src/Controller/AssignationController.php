<?php

namespace App\Controller;

use App\Entity\UE;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Connection;

class AssignationController extends AbstractController
{
    #[Route('/admin/assignation', name: 'app_assignation')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $ues = $entityManager->getRepository(UE::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/assignation.html.twig', [
            'styles' => [],
            'scripts' => [],
            'header' => '',
            'currentPage' => 'app_assignation',
            'ues' => $ues,
            'users' => $users,
        ]);
    }

    #[Route('/admin/assignation/confirm', name: 'admin_assignation_confirm', methods: ['POST'])]
    public function confirmAssignation(Request $request, Connection $connection): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userIds = $data['userIds'] ?? [];
        $added = $data['addedUeCodes'] ?? [];
        $removed = $data['removedUeCodes'] ?? [];

        if (empty($userIds)) {
            return new JsonResponse(['status' => 'error', 'message' => 'No users selected'], 400);
        }

        try {
            // Handle additions
            foreach ($added as $ueCode) {
                $ueId = $connection->fetchOne('SELECT id FROM ue WHERE code = :code', ['code' => $ueCode]);
                if (!$ueId) continue;

                foreach ($userIds as $userId) {
                    $connection->executeStatement(
                        'INSERT INTO ue_user (user_id, ue_id)
                     SELECT :userId, :ueId
                     WHERE NOT EXISTS (
                         SELECT 1 FROM ue_user WHERE user_id = :userId AND ue_id = :ueId
                     )',
                        ['userId' => $userId, 'ueId' => $ueId]
                    );
                }
            }

            // Handle removals
            foreach ($removed as $ueCode) {
                $ueId = $connection->fetchOne('SELECT id FROM ue WHERE code = :code', ['code' => $ueCode]);
                if (!$ueId) continue;

                foreach ($userIds as $userId) {
                    $connection->executeStatement(
                        'DELETE FROM ue_user WHERE user_id = :userId AND ue_id = :ueId',
                        ['userId' => $userId, 'ueId' => $ueId]
                    );
                }
            }

            return new JsonResponse(['status' => 'success']);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    #[Route('/admin/assignation/fetch', name: 'admin_assignation_fetch', methods: ['POST'])]
    public function fetchAssignations(Request $request, Connection $connection): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'] ?? null;

        if (!$userId) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing userId'], 400);
        }

        $sql = <<<SQL
        SELECT ue.code
        FROM ue
        JOIN ue_user ON ue.id = ue_user.ue_id
        WHERE ue_user.user_id = :userId
    SQL;
        // récupère les codes UE assignés à l’utilisateur
        $assignedUes = $connection->fetchFirstColumn($sql, ['userId' => $userId]);

        return new JsonResponse(['status' => 'success', 'assignedUes' => $assignedUes]);
    }
}