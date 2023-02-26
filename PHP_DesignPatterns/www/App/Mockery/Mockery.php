<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Mockery;

use App\Concretes\Categories;
use App\Concretes\Channels;

class Mockery
{
    public function users(): MockUsers
    {
        $users = new MockUsers();
        $users->add(
            [
                'id' => uniqid(),
                'name' => 'Mock User 1',
                'email' => 'mock_user1@email.com',
                'phoneNumber' => ['11111111'],
                'categories' => new Categories(['Sports', 'Finance', 'Movies']),
                'channels' => new Channels(['SMS', 'Push']),
            ]
        );
        $users->add(
            [
                'id' => uniqid(),
                'name' => 'Mock User 2',
                'email' => 'mock_user2@email.com',
                'phoneNumber' => ['22222222'],
                'categories' => new Categories(['Sports']),
                'channels' => new Channels(['Push', 'Email']),
            ]
        );
        $users->add(
            [
                'id' => uniqid(),
                'name' => 'Mock User 3',
                'email' => 'mock_user3@email.com',
                'phoneNumber' => ['33333333'],
                'categories' => new Categories(['Sports', 'Movies']),
                'channels' => new Channels(['SMS', 'Email']),
            ]
        );
        $users->add(
            [
                'id' => uniqid(),
                'name' => 'Mock User 4',
                'email' => 'mock_user4@email.com',
                'phoneNumber' => ['44444444'],
                'categories' => new Categories(['Finance', 'Movies']),
                'channels' => new Channels(['SMS', 'Email', 'Push']),
            ]
        );
        $users->add(
            [
                'id' => uniqid(),
                'name' => 'Mock User 5',
                'email' => 'mock_user5@email.com',
                'phoneNumber' => ['55555555'],
                'categories' => new Categories(['Finance']),
                'channels' => new Channels(['SMS', 'Email', 'Push']),
            ]
        );
        return $users;
    }
}
