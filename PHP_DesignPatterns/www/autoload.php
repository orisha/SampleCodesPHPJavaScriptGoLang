<?php
spl_autoload_register(function (string $className): void{
    require_once (__DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php');
});