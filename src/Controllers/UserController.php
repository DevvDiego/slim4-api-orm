<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Traits\ResponseTrait;

class UserController {
    use ResponseTrait;

    // Set the dependency of the db
    public function __construct(Capsule $db) {}

    public function new(Request $request, Response $response){
        $data = $request->getParsedBody();

        $user = \App\Models\User::query()->firstOrCreate(
            ["email" => $data["email"]], // the unique field
            [
                "name" => $data["name"], 
                "password" => password_hash($data["password"], PASSWORD_BCRYPT),
                "role" => $data["role"]
            ]
        );

        if ($user->wasRecentlyCreated) {
            return $this->success(res:$response, msg:"User created successfully", code:201);
        }

        return $this->error(res:$response, msg:"User already exists", code:409);

    }

    public function showById(Response $response, $args) {
        $user = \App\Models\User::query()->find($args["id"]);

        if (!$user) {
            return $this->error(res:$response, msg:"User nor found", code:404);
        }

        return $this->success($response, $user);
    }

}

?>