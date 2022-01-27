<?php

namespace App\Controller\Invit;

use App\Repository\UserRepository;
use App\Services\Invit\Invit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InvitController extends AbstractController
{
    /**
     * @Route("/users/{userId}/groupes/{groupId}/sendInvit", name="sendInvit", methods={"POST"})
     */
    public function sendInvitAccept(Request $request): JsonResponse
    {
        Invit::sendMail($request->get('userId'), $request->get('groupId'));
        return new JsonResponse(
            [
                'message' => 'Mail sent'
            ], 200
        );
    }

    /**
     * @Route("/users/{userId}/groupes/{groupId}/invites/{invitId}/accept", name="invit_accept", methods={"GET"})
     */
    public function invitAccept(Request $request): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'Le fréro à accepté',
                'isAccepted' => true
            ], 200
        );
    }

    /**
     * @Route("/users/{userId}/groupes/{groupId}/invites/{invitId}/decline", name="invit_decline", methods={"GET"})
     */
    public function invitDecline(Request $request): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'Pas acceptée',
                'isAccepted' => false
            ], 200
        );
    }


}
