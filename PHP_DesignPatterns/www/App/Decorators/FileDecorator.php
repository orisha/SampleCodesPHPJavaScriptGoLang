<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Decorators;

use App\Contracts\Decorator;

class FileDecorator implements Decorator
{
    private array $data = [
        'id', 'user', 'message', 'channel', 'status', 'date'
    ];

    public function __construct(
        private string $id,
        private string $user,
        private string $message,
        private string $channel,
        private string $status,
        private string $date
    )
    {
        return $this;
    }

    public function __toArray(): array
    {
        $thisArray = [];
        foreach ($this->data as $key)
        {
            $thisArray[$key] = $this->{$key};
        }
        return $thisArray;
    }
}
