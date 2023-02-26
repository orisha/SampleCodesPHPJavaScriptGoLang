<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Notification;

use App\Contracts\NotificatificatoAdapter;
use App\Contracts\User;
use App\Loguer\ProofOfConcept;

class EmailNotification implements NotificatificatoAdapter
{
    public function sendNotification(User $user, string $message): void
    {
        (new ProofOfConcept($user, $message, self::class));
    }
}
