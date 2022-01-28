<?php

namespace App\Services\Invit;

use App\Entity\User;
use App\Repository\GroupRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Invit
{

    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function sendMail(int $userId, $groupId)
    {
        $invitId = Uuid::v6();
        $url = '/users/' . $userId . '/groupes/' . $groupId . '/invites/' . $invitId . '/accept';
        $eUser = $this->userRepository->find($userId);
        $userEmail = $eUser->getEmail();

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('antoinemousset1999@gmail.com')
            ->setPassword('rzdipdhyxqlsnhzs')
    ;

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('Wonderful Subject'))
            ->setFrom('antoinemousset1999@gmail.com')
            ->setTo($userEmail)
            ->setBody('This is your invite http://localhost:8000' . $url)
        ;

        $mailer->send($message);
    }

    public function verifUser($groupId, $userId): bool
    {
        $eGroup = $this->groupRepository->find($groupId);
        $eUser = $this->userRepository->find($userId);
        $groupsTheirIn = $eUser->getGroupsTheirIn();
//        dd($groupsTheirIn);
        foreach ($groupsTheirIn as $group) {
            if ($group->getId() == $groupId ){
               return false;
            }
        }
        return true;
    }
}
