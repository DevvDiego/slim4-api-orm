<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Auth\Auth;
use App\Traits\ResponseTrait;

class SessionController {
    use ResponseTrait;

    public function show(Request $request, Response $response): Response {
        // Auth::user() ya fue seteado por el middleware
        $user = Auth::user();
        
        if (!$user) {
            return $this->error(
                res: $response,
                msg: "No authenticated session",
                code: 401
            );
        }

        return $this->success(
            res: $response,
            msg: "Session active",
            data: [
                "id"    => $user->sub,
                "email" => $user->email ?? null,
                "role"  => $user->role ?? "user",
                "exp"   => $user->exp ?? null
            ]
        );
    }

    public function destroy(Request $request, Response $response): Response {
        // JWT is stateless, its clients responsability to discard the token
        // or we could also set a blacklist until exp time?
        
        return $this->success(
            res: $response,
            msg: "Logout successful (client must discard token)"
        );
    }
}