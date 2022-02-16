<?php
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

require 'vendor/autoload.php';

/**
 * @return Transport\TransportInterface
 */
function configTransport()
{
    $dsn = require('dsn-config.php');
    return Transport::fromDsn($dsn);
}

/**
 * @param array $data
 * @param string $layout
 * @return Email
 */
function createMessage(array $data, string $layout)
{
    $message = new Email();

    $message->to($data['email']);
    $message->from("keks@phpdemo.ru");
    $message->subject("Ваша ставка победила");
    $message->html($layout);

    return $message;
}

/**
 * @param $transport
 * @param $message
 * @return void
 */
function sendEmail($transport, $message)
{
    $mailer = new Mailer($transport);

    try {
        $mailer->send($message);
    } catch (TransportExceptionInterface $e) {
        echo 'Выброшено исключение: ',  $e->getDebug(), "\n";
        exit();
    }
}
