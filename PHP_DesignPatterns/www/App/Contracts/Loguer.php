<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Contracts;

interface Loguer
{
    public function add(User $user, string $message, string $channel): string;
    public function get(string $id): LoguerEntry;
    public function all(): array;
}
