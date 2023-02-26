<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\Channels as ContractsChannels;

class Channels implements ContractsChannels
{
    public function __construct(protected array $channels)
    {
        return $this;
    }
    public function channels(): array
    {
        return $this->channels;
    }
}
