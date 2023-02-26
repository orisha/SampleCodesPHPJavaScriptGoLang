<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Requests;

use App\Contracts\Request;
use App\Concretes\RequestData;
use App\Concretes\RequestType;

final class GetRequest implements Request
{
    public function __construct(protected array $data)
    {
        return $this;
    }

    public function method(): RequestType
    {
        return new RequestType('GET');
    }
    public function data(): RequestData
    {
        return new RequestData($this->data);
    }
}
