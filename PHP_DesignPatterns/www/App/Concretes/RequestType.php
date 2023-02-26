<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\RequestType as AbstractRequestType;
use Stringable;

final class RequestType implements AbstractRequestType, Stringable
{
    public function __construct(protected string $method)
    {
        return $this;
    }
    public function __toString(): string
    {
        return $this->method;
    }
}
