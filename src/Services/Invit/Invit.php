<?php

namespace App\Services\Invit;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Invit
{

    public static function sendMail($userId, $groupId)
    {
        $invitId = Uuid::v6();
        $url = '/users/' . $userId . '/groupes/' . $groupId . '/invites/' . $invitId . '/accept';
//        $user =  UserRepository::class->findBy($userId);
//
//        $userEmail = $user->getEmail();

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('antoinemousset1999@gmail.com')
            ->setPassword('rzdipdhyxqlsnhzs')
    ;

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('Wonderful Subject'))
            ->setFrom('antoinemousset1999@gmail.com')
            ->setTo('antoinemousset1999@gmail.com')
            ->setBody('This is your invite http://localhost:8000' . $url)
        ;

        $mailer->send($message);
    }
}
