<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\User as User;
use App\Traits\ResponseTrait;

class AuthController {
    use ResponseTrait;

    public function __construct(Capsule $db) {}

    public function login(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $email = $data["email"];
        $password = $data["password"];

        $user = User::query()->where("email", $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return $this->error(
                res:$response,
                msg:"Invalid credentials",
                code:401
            );

        }

        $jwtManager = new \App\Auth\JWTManager($_ENV["JWT_SECRET"]);
        $token = $jwtManager->createToken($user);

        return $this->success(
            res:$response,
            data:[
                "token" => $token,
                "expires_in" => 24 * 3600 // 24 hrs
            ],
            msg:"Log in successfull"
        );
    }

}

?>