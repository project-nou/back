<?php

namespace App\Controller\InvitController;

use App\Services\Invit\Invit;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InvitController
{
    /**
     * @Route("/users/{userId}/invites/{invitId}/accept", name="invit_accept", methods={"GET"})
     */
    public function sendInvitAccept(Request $request, MailerInterface $mailer): JsonResponse
    {
        Invit::sendMailAccept($request->userId, $request->invitId);
        return new JsonResponse(
            [
                'message' => 'Mail sent'
            ], 200
        );
    }

    /**
     * @Route("/users/{userId}/invites/{invitId}/decline", name="upload_file", methods={"GET"})
     */
    public function sendInvitDecline(Request $request): JsonResponse
    {
        Invit::sendMailDecline($userId, $invitId);
        return new JsonResponse(
            [
                'message' => 'Mail sent'
            ], 200
        );
    }

}
