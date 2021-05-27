<?php


namespace App\Entity;
use App\Interfaces\MailerInterface;

class ProxyMailer implements MailerInterface
{


    /**
     * @var MailerInterface $mailer
     */

    private $mailer;

    private $title;

    private $body;

    private $mail;

    public function __construct()
    {
        $this->mailer = new Mailer();

    }

    public function createMessage($title, $body, $mail)
    {

        if($this->title == $title && $this->body == $body && $this->mail == $mail){

            $message =  $this->mailer ->getMessage();
            $this->mailer->sendMessage($message);

        } else {

              $this->title = $title;
              $this->body = $body;
              $this->mail = $mail;

              $this->mailer->createMessage($this->title, $this->body,$this->mail);
        }

    }


}