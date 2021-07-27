<?php

namespace SalamWaddah\Mandrill;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class MandrillChannel
{
    private $client;
    private $url = 'https://mandrillapp.com/api/1.0/messages/send-template.json';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        /**
         * @var $message MandrillMessage
         */
        $message = $notification->toMandrill($notifiable);

        $this->client->post($this->url, [
            'json' => $this->toArray($message),
        ]);
    }

    public function toArray(MandrillMessage $message)
    {
        return [
            'key' => env('MANDRILL_SECRET', Config::get('mail.mandrill.key')),
            'template_name' => $message->view,
            'template_content' => [],
            'message' => $message->structure(),
        ];
    }
}
