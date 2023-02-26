<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\Response as AbstractResponse;

final class ResponseError implements AbstractResponse
{
    public function __construct(protected string $responseError)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode(['error' => $responseError]);
        die;
    }
}
