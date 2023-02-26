<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Mockery;

use App\Concretes\User;
use App\Contracts\Mockery;

class MockUsers implements Mockery
{
    public array $users = [];
    public function add(array $data): void
    {
        $this->users[] = new User(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['phoneNumber'],
            $data['categories'],
            $data['channels']
        );
    }
}
