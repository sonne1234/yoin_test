<?php

namespace App\Service;

use App\Notification\Notification;
use Psr\Log\LoggerInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * @var string
     */
    private $fromEmail;

    private $defaultEmail;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->logger = $logger;
    }

    public function setParams(string $fromEmail, string $fromName, string $defaultEmail)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->defaultEmail = $defaultEmail;
    }

    public function send(Notification $notification)
    {
        if ('' === $notification->emailTo()) {
            return;
        }

        try {
            $this->mailer->getTransport()->stop();
        } catch (\Exception $e) {
        }

        $message = \Swift_Message::newInstance();

        $notification->addAttachment($message);

        try {
            $this->mailer->send(
                $message
                    ->setSubject($notification->subject())
                    ->setTo($notification->emailTo())
                    ->addFrom($this->fromEmail, $this->fromName)
                    ->setBody(
                        $this->twig->render(
                            $notification->templatePath(),
                            [
                                'fromEmailParameter' => $this->fromEmail,
                                'defaultEmailParameter' => $this->defaultEmail,
                            ] + $notification->templateVars()
                        ),
                        'text/html'
                    )
            );
        } catch (\Exception $e) {
            $this->logger->error(
                'Error when sending email to "'.$notification->emailTo()
                .'" with subject "'.$notification->subject().'"'."\r\n"
                .get_class($e).', '.$e->getMessage().', code: '.$e->getCode().'.'
            );
        }

        try {
            $this->mailer->getTransport()->stop();
        } catch (\Exception $e) {
        }
    }
}
