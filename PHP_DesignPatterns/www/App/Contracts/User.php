<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Contracts;

interface User
{
    public function id(): string;
    public function name(): string;
    public function email(): string;
    public function phoneNumber(): array;
    public function categories(): Categories;
    public function channels(): Channels;
}
