<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Loguer;

final class Debuger
{

    public static function kill($params): void
    {
        echo '<pre>';
        print_r($params);
        die;
    }
    public static function show($params): void
    {
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    }
}
