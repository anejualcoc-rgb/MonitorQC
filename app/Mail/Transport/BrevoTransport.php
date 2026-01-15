<?php

namespace App\Mail\Transport;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class BrevoTransport extends AbstractTransport
{
    protected $api;

    public function __construct()
    {
        parent::__construct();
        
        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'api-key',
            config('services.brevo.key')
        );
        
        $this->api = new TransactionalEmailsApi(null, $config);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $from = $email->getFrom()[0];
        $to = [];
        
        foreach ($email->getTo() as $address) {
            $to[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: $address->getAddress()
            ];
        }

        $sendSmtpEmail = new SendSmtpEmail([
            'sender' => [
                'email' => $from->getAddress(),
                'name' => $from->getName() ?: config('mail.from.name')
            ],
            'to' => $to,
            'subject' => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody() ?: $email->getTextBody(),
        ]);

        try {
            $this->api->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            Log::error('Brevo email error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}