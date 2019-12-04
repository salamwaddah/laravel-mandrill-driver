<?php

namespace SalamWaddah\Mandrill;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class MandrillMessage extends MailMessage
{
    const MERGE_LANGUAGE_HANDLEBARS = 'handlebars';
    const MERGE_LANGUAGE_MAILCHIMP = 'mailchimp';

    private $tos = [];
    private $mergeLanguage = self::MERGE_LANGUAGE_HANDLEBARS;

    public function addTo(string $to): self
    {
        array_push($this->tos, $to);

        return $this;
    }

    public function addTos(array $tos): self
    {
        foreach ($tos as $to) {
            $this->addTo($to);
        }

        return $this;
    }

    public function fromEmail(string $email): self
    {
        $this->from[0] = $email;

        return $this;
    }

    public function fromName(string $name): self
    {
        $this->from[1] = $name;

        return $this;
    }

    public function templateName(string $template, $mergeLanguage = self::MERGE_LANGUAGE_HANDLEBARS): self
    {
        $this->view = $template;
        $this->mergeLanguage = $mergeLanguage;

        return $this;
    }

    public function content(array $data): self
    {
        $this->viewData = $data;

        return $this;
    }

    public function structure(): array
    {
        return [
            'merge_language' => $this->mergeLanguage,
            'to' => $this->getTo(),
            'subject' => $this->subject,
            'from_email' => $this->from[0] ?? Config::get('mail.from.address'),
            'from_name' => $this->from[1] ?? Config::get('mail.from.name'),
            "global_merge_vars" => $this->mapGlobalVars()
        ];
    }

    private function getTo(): array
    {
        return array_map(function ($to) {
            return ['email' => $to];
        }, $this->tos);
    }

    private function mapGlobalVars(): array
    {
        return array_map(function ($value, $key) {
            return ['name' => $key, 'content' => $value];
        }, $this->viewData, array_keys($this->viewData));
    }
}
