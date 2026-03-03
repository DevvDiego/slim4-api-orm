<?php

namespace App\Controllers;

use App\Auth\JWTManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\User as User;
use App\Traits\ResponseTrait;

class AuthController {
    use ResponseTrait;
    private JWTManager $jwt;

    public function __construct(Capsule $db, JWTManager $jwt) {
        $this->jwt = $jwt;
    }

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

        $token = $this->jwt->createToken($user);

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