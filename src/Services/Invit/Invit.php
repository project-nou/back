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

    public function sendMail(int $userId, $groupId, $body, $subject, $userEmail)
    {
        $transport = (new Swift_SmtpTransport('smtp.gmail.com',
                465, 'ssl'))
            ->setUsername('antoinemousset1999@gmail.com')
            ->setPassword('rzdipdhyxqlsnhzs');
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
        // Create a message
        $message = (new Swift_Message($subject))
            ->setFrom('antoinemousset1999@gmail.com')
            ->setTo($userEmail)
            ->setBody($body);
        $mailer->send($message);
    }

    public function verifUser($groupId, $userId): bool
    {
        $eUser = $this->userRepository->find($userId);
        $groupsTheirIn = $eUser->getGroupsTheirIn();
        foreach ($groupsTheirIn as $group) {
            if ($group->getId() == $groupId ){
               return false;
            }
        }
        return true;
    }
}
