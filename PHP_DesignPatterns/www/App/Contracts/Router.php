<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Contracts;

interface Router
{
    public function post(string $method, Controller $controller): Response;
    public function get(string $method, Controller $controller): Response;
}
