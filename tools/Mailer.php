<?php

namespace Mikelnavarro\Eurofilm\tools;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $config;
    private $mail;

    public function __construct(array $config)
    {
        $mail = new PHPMailer(true);
        $this->config = $config;


        $this->mail->isSMTP();
        $this->mail->Host       = $this->config['host'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->config['username'];
        $this->mail->Password   = $this->config['password'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = $this->config['port'];
        $this->mail->CharSet    = $this->config['charset'];
    }

    public function send($to, string $subject, string $body, array $options)
    {

        try {
            // IMPORTANTE: limpiar estado en cada envío
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            // Remitente
            $fromName = $options['from_name'] ?? 'Sistema';
            $this->mail->setFrom($this->config['username'], $fromName);

            // Destinatarios
            if (is_array($to)) {
                foreach ($to as $email) {
                    $this->mail->addBCC($email);
                }
            } else {
                $this->mail->addAddress($to);
            }

            // Adjuntos
            if (!empty($options['attachments'])) {
                foreach ($options['attachments'] as $file) {
                    $this->mail->addAttachment($file['tmp_name'], $file['name']);
                }
            }

            // Contenido
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            return $this->mail->send();
        } catch (Exception $e) {
            return $this->mail->ErrorInfo;
        }
    }
}
