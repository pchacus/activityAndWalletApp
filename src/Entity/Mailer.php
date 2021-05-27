<?php


namespace App\Entity;

use Swift_Mailer;
use Swift_Message;
use Swift_Mime_SimpleMessage;
use Swift_SmtpTransport;
use App\Interfaces\MailerInterface;

class Mailer implements MailerInterface
{


    /**
     * @var Swift_Mime_SimpleMessage $message
     */
    private $message;

    /**
     * @var Swift_Mailer $mailer
     */
    private $mailer;

    public function __construct()
    {
        $transport = (new \Swift_SmtpTransport('poczta.interia.pl',587))
            ->setUsername("aktywnosci.up@interia.pl")
            ->setPassword("Qwerty1234!")
        ;

        $this->mailer = new \Swift_Mailer($transport);
    }

    /**
     * @return Swift_Mime_SimpleMessage
     */
   public function getMessage(){

        return $this->message;
   }


    public function createMessage($title, $body, $mail){


        $this->message = (new \Swift_Message($title))
            ->setFrom("aktywnosci.up@interia.pl")
            ->setTo($mail)
            ->setBody($body);
        ;

       $this->sendMessage($this->message);

    }

    /**
     * @param Swift_Mime_SimpleMessage $message
     *
     */
    public function sendMessage($message)
    {
        $this->mailer->send($message);
    }

}