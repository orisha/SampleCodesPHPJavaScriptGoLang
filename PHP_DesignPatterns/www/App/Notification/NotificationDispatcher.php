<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Notification;

use App\Contracts\Loguer;
use App\Mockery\Mockery;

class NotificationDispatcher
{
    protected Notificator $notificator;
    protected array $deferredStack = [];

    public function __construct(private string $message, private array $categories, protected Loguer $loguer)
    {
        $this->notificator = new Notificator($loguer);
    }

    public function dispatch(): void
    {
        foreach ((new Mockery)->users()->users as $user) {
            $userCategories = $user->categories()->categories();
            $categoriesToSend = array_intersect($this->categories, $userCategories);
            if (!empty($categoriesToSend)) {
                $this->notificator->sendNotification($user, $this->message);
            }
        }
    }
}
