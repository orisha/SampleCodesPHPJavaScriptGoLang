<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Concretes;

use App\Contracts\Response as AbstractResponse;

final class Response implements AbstractResponse
{
    public function __construct(protected array $responseData)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        $responseData['success'] = true;
        echo json_encode($responseData);
        die;
    }
}
