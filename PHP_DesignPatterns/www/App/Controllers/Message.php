<?php
/**
 * @author Diego Favero
 * @source github/com/orisha/SampleCodesPHPJavaScriptGoLang/PHP_DesignPatterns
 * @since Feb 2023
 */

namespace App\Controllers;

use App\Contracts\Controller;
use App\Concretes\Response;
use App\Concretes\ResponseError;
use App\Contracts\Loguer;
use App\Contracts\Response as ContractsResponse;
use App\Loguer\File;
use App\Notification\NotificationDispatcher;
use App\Requests\GetRequest;
use App\Requests\PostRequest;

class Message implements Controller
{
    protected Loguer $loguer;

    public function __construct()
    {
        $this->loguer = new File();
    }

    public function send(PostRequest $post): ContractsResponse
    {
        $data = $post->data()->__toArray();
        if (empty($data['categories'])) {
            return new ResponseError('Category cant be empty');
        }
        if (empty($data['message'])) {
            return new ResponseError('Message cant be empty');
        }
        $notification = new NotificationDispatcher($data['message'], $data['categories'], $this->loguer);
        $notification->dispatch();
        return new Response([]);
    }

    public function get(GetRequest $get): ContractsResponse
    {
        return new Response(
            $this->loguer->all()
        );
    }
}
