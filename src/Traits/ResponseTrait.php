<?php

namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;

trait ResponseTrait {

    private function createResponse(
        Response $response,
        bool $success,
        string $message,
        int $statusCode,
        mixed $data = null
    ): Response {
        
        $responseData = [
            "success" => $success,
            "message" => $message,
            "data" => $data
        ];
        
        $response->getBody()->write(json_encode($responseData));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

    public function success(Response $res, $data = null, $msg = "success") {
        return $this->createResponse($res, true, $msg, 200, $data);
    }

    public function created(Response $res, $data = null) {
        return $this->createResponse($res, true, "Created successfully", 201, $data);
    }

    public function error(Response $res, $msg = "error", $code = 400) {
        return $this->createResponse($res, false, $msg, $code);
    }
}