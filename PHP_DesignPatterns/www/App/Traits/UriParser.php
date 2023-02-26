<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Traits;

use App\Contracts\Request;
use App\Requests\GetRequest;
use App\Requests\PostRequest;

trait UriParser
{
    private function parseUri(): array
    {
        $uri = $_SERVER['REQUEST_URI'];
        $arrayUri = explode(DIRECTORY_SEPARATOR.'api.php'.DIRECTORY_SEPARATOR, $uri);
        $route = end($arrayUri);
        return explode(DIRECTORY_SEPARATOR, $route);
    }

    public function getRoute(): string
    {
        $arrayRoute = $this->parseUri();
        return reset($arrayRoute);
    }

    public function getRouteParams(): array
    {
        $arrayRoute = $this->parseUri();
        unset ($arrayRoute[0]);
        return array_values($arrayRoute);
    }

    public function setRequest(): ?Request
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post')
        {
            return new PostRequest();
        }
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'get')
        {
            return new GetRequest($this->getRouteParams());
        }
        return null;
    }
}
