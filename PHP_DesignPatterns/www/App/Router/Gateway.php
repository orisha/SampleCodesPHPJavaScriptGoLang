<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Router;

use App\Contracts\Request;
use App\Contracts\Response;
use App\Exceptions\RouteNotFound;
use App\Traits\UriParser;
use App\Routes\Routes;

final class Gateway
{
    use UriParser;

    /**
     *
     * @string Route to match
     */
    protected string $route;
    
    /**
     *
     * @string method to match
     */
    protected string $method;

    /**
     * Request
     */
    protected Request $request;

    public function __construct()
    {
        $this->route = $this->getRoute();
        $this->request = $this->setRequest();
        $this->method = $this->request->method()->__toString();
        return $this->redirect();
    }

    private function redirect(): ?Response
    {
        $allRoutes = Routes::$allRoutes;
        if (in_array($this->route, array_keys($allRoutes[$this->method]))) {
            return $this->callRoute($allRoutes[$this->method][$this->route]);
        } else {
            throw new RouteNotFound('Route Not Found');
        }
    }

    private function callRoute(string $controller): Response
    {
        if ($this->method === 'POST') {
            $routeController = new ($controller);
            return $routeController->{$this->route}($this->request);
        }
        if ($this->method === 'GET') {
            $routeController = new ($controller);
            return $routeController->{$this->route}($this->request);
        }
    }
}
