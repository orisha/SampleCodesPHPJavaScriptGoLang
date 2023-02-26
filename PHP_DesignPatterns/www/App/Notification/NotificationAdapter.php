<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Notification;

class NotificationAdapter
{
    public static array $mapper = [
        'email' => EmailNotification::class,
        'sms' => SmsNotification::class,
        'push' =>  PushNotification::class,
    ];
}
