<?php

namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;

trait ResponseTrait {

    private function json(
        Response $res, 
        int $status, 
        array $payload
    ): Response {
        $res->getBody()->write(json_encode($payload));

        return $res->withHeader('Content-Type', 'application/json')
                   ->withStatus($status);
    }

    public function success(
        Response $res, 
        mixed $data = null, 
        string $msg = "Success", 
        int $code = 200
    ): Response {

        return $this->json($res, $code, [
            "success" => true,
            "message" => $msg,
            "data"    => $data
        ]);

    }

    public function error(
        Response $res, 
        mixed $data = null, 
        string $msg = "Success", 
        int $code = 400
    ): Response {

        return $this->json($res, $code, [
            "success" => false,
            "message" => $msg,
            "data"    => $data
        ]);

    }
}