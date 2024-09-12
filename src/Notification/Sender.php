<?php

namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Sender
{
    protected $mailer;

    public function __construct( MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

public function sendNewUserNotificationToAdmin(UserInterface $user) :void
{
//file_put_contents("debug.txt", $user->getEmail());

    $message =new Email();
    $message->from('acounts@series.com')
        ->to('admin@series.com')
        ->subject('new account created')
        ->html('<h1>New account created</h1>' . $user->getEmail());

    $this->mailer->send($message);
}
}