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

final class PostRequest implements Request
{
    public function __toString(): string
    {
        return 'POST';
    }

    public function method(): RequestType
    {
        return new RequestType('POST');
    }
    public function data(): RequestData
    {
        $inputJSON = file_get_contents('php://input');
        return new RequestData(json_decode($inputJSON, true));
    }
}
