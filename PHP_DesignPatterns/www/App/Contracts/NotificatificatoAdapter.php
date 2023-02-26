<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Contracts;

interface NotificatificatoAdapter
{
    public function sendNotification(User $user, string $message): void;
}
