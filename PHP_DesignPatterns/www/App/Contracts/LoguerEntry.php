<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Contracts;

interface LoguerEntry
{
    public function message(): string;
    public function status(): string;
    public function user(): User;
    public function dateCreated(): \DateTime;
}
