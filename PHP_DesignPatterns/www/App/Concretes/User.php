<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\User as ContractsUser;

class User implements ContractsUser
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected string $email,
        protected array $phoneNumber,
        protected Categories $categories,
        protected Channels $channels
    )
    {
        return $this;
    }

    public function id(): string
    {
        return $this->id;
    }
    public function name(): string
    {
        return $this->name;
    }
    public function email(): string
    {
        return $this->email;
    }
    public function phoneNumber(): array
    {
        return $this->phoneNumber;
    }
    public function categories(): Categories
    {
        return $this->categories;
    }
    public function channels(): Channels
    {
        return $this->channels;
    }
}
