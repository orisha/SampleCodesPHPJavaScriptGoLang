<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\RequestData as AbstractRequestData;

final class RequestData implements AbstractRequestData
{
    public function __construct(protected array $data)
    {
        return $this;
    }

    public function __toArray(): array
    {
        return $this->data;
    }
}
