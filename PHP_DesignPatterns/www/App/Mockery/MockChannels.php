<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Mockery;

use App\Concretes\Channels as ChannelsModel;
use App\Contracts\Mockery;

class MockChannels implements Mockery
{
    public array $channels = [];
    public function add(array $data): void
    {
        $this->channels[] = new ChannelsModel($data['channels']);
    }
}
