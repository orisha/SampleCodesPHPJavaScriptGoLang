<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Notification;

use App\Exceptions\NotificationFactoryExcepetion;

class NotificationFactory
{
    public function createNotification($type)
    {
        $type = strtolower($type);
        $sendersMap = NotificationAdapter::$mapper;
        if (in_array($type, array_keys($sendersMap))) {
            return (new $sendersMap[$type]);
        }
        throw new NotificationFactoryExcepetion("Invalid notification type");
    }
}
