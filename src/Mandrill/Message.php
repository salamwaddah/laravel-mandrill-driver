<?php

namespace SalamWaddah\Mandrill;

use Illuminate\Notifications\Messages\MailMessage;

class Message extends MailMessage
{
    private $tos = [];

    public function addTo($to)
    {
        array_push($this->tos, $to);
        return $this;
    }

    public function addTos(array $tos)
    {
        array_merge($this->tos, $tos);
        return $this;
    }

    public function getTo()
    {
        return $this->tos;
    }
}
