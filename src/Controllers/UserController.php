<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Traits\ResponseTrait;

class UserController {
    use ResponseTrait;

    // nothing here, just sets the dependency of the db
    public function __construct(Capsule $db) {}

    public function showUser(Request $request, Response $response, $args) {
        $user = \App\Models\User::find($args["id"]);

        if (!$user) {
            return $this->error($response, "Usuario no encontrado", 404);
        }

        return $this->success($response, $user);
    }

}

?>