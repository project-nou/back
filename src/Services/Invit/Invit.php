<?php

namespace App\Services\Invit;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class Invit
{

    public static function sendMail($userEmail, $url)
    {
        $email = (new Email())
            ->from('antoinemousset1999@gmail.com')
            ->to($userEmail)
            ->subject('Invit')
            ->text('This is your invite' . $url );
//            ->html('');

        $mailer->send($email);
    }


    public static function sendMailAccept($userId, $invitId)
    {
        $url = '/invites/' . $invitId . '/users/' . $userId . '/accept';
        $user = UserRepository::class->findBy($userId);
        $userEmail = $user->getEmail();
        self::sendMail($userEmail, $url);
    }

    public static function sendMailDecline($userId, $invitId)
    {
        $url = '/invites/' . $invitId . '/users/' . $userId . '/decline';
        $user = UserRepository::class->findBy($userId);
        $userEmail = $user->getEmail();
        self::sendMail($userEmail, $url);
    }


}
