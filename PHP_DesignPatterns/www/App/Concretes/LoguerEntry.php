<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use DateTime;

class LoguerEntry
{
    public function __construct(
        protected string $message,
        protected string $status,
        protected User $user,
        protected DateTime $dateCreated = new \DateTime()
    )
    {
        return $this;
    }
    public function message(): string
    {
        return $this->message;
    }
    public function status(): string
    {
        return $this->status;
    }
    public function user(): User
    {
        return $this->user;
    }
    public function dateCreated(): \DateTime
    {
        return $this->dateCreated;
    }
}
