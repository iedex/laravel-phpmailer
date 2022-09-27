<?php
namespace LaravelPHPMailer;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class PHPMailerTransporter extends AbstractTransport
{
    /**
     * @var PHPMailer
     */
    protected $client;

    /**
     * @param PHPMailer $client
     */
    public function __construct(PHPMailer $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * @param SentMessage $message
     */
    protected function doSend(SentMessage $message): void
    {
        $this->client->clearAllRecipients();
        $this->client->clearAttachments();
        $this->client->clearCustomHeaders();
        $this->client->clearReplyTos();

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = $email->getFrom();

        if (!empty($from)) {
            $from = $from[0];
            $this->client->setFrom($from->getAddress(), $from->getName());
        }

        foreach ($email->getTo() as $to) {
            $this->client->addAddress($to->getAddress(), $to->getName());
        }

        foreach ($email->getCc() as $cc) {
            $this->client->addCC($cc->getAddress(), $cc->getName());
        }

        foreach ($email->getBcc() as $bcc) {
            $this->client->addBCC($bcc->getAddress(), $bcc->getName());
        }

        foreach ($email->getReplyTo() as $replyTo) {
            $this->client->addReplyTo($replyTo->getAddress(), $replyTo->getName());
        }

        if ($email->getReturnPath() !== null) {
            $this->client->Sender = $email->getReturnPath();
        }

        $this->client->Subject = $email->getSubject() ?: '';

        $textBody = $email->getTextBody();
        $htmlBody = $email->getHtmlBody();

        $this->client->isHTML(!empty($htmlBody));

        if (empty($htmlBody)) {
            $this->client->Body = $textBody;
        } else {
            $this->client->Body = $htmlBody;
            $this->client->AltBody = $textBody;
        }

        $this->client->isMail();
        $this->client->XMailer = ' ';

        $headers = [];

        /** @var \Symfony\Component\Mime\Header\HeaderInterface $header */
        foreach ($email->getPreparedHeaders() as $header) {
            $headers[$header->getName()] = $header->getBodyAsString();
        }

        $this->client->CharSet = empty($headers['Charset'])
            ? PHPMailer::CHARSET_UTF8
            : $headers['Charset'];

        foreach ($headers as $header => $value) {
            if (!in_array($header, ['MIME-Version', 'X-Mailer'])) {
                $this->client->addCustomHeader($header, $value);
            }
        }

        if (!$this->client->send()) {
            throw new \Exception('Message could not be sent. Mailer Error: ' . $this->client->ErrorInfo);
        }
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'PHPMailer';
    }
}