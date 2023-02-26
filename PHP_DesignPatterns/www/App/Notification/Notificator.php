<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Notification;

use App\Concretes\User;
use App\Contracts\Loguer;

class Notificator
{
    private NotificationFactory $notificationFactory;
    public function __construct(private Loguer $loguer)
    {
        $this->notificationFactory = new NotificationFactory();
    }

    public function sendNotification(User $recipient, $message)
    {
        foreach ($recipient->channels()->channels() as $channel) {
            $notification = $this->notificationFactory->createNotification($channel);
            $notification->sendNotification($recipient, $message);
            $this->loguer->add($recipient, $message, $channel);
        }
    }
}
