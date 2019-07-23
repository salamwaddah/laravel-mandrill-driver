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

        $this->client->request('POST', $this->url, [
            'json' => $this->toArray($message)
        ]);
    }

    public function toArray(MandrillMessage $message)
    {
        return [
            'key' => Config::get('mail.mandrill.key'),
            'template_name' => $message->view,
            'template_content' => [],
            'message' => [
                'merge_language' => 'handlebars',
                'to' => array_map(function ($to) {
                    return ['email' => $to];
                }, $message->getTo()),
                'subject' => $message->subject,
                'from_email' => $message->from[0] ?? Config::get('mail.from.address'),
                'from_name' => $message->from[1] ?? Config::get('mail.from.name'),
                "global_merge_vars" => $message->viewData
            ]
        ];
    }
}
