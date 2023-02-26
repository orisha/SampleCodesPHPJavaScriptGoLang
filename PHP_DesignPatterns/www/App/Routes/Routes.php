<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Routes;

use App\Controllers\Message;

class Routes
{
    public static array $allRoutes = [
        'POST' => [
            'send' => Message::class,
        ],
        'GET' => [
            'get' => Message::class,
        ],
    ];
}
