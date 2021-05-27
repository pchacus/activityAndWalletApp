<?php


namespace App\Interfaces;


interface MailerInterface
{
    /**
     * @param $title
     * @param $body
     * @param $mail
     * @return mixed
     */
    public function createMessage($title, $body, $mail);

}