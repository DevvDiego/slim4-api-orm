<?php
namespace App\Middleware;

use App\Traits\ResponseTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response;

class ValidationMiddleware{
    use ResponseTrait;
    private array $requiredFields;

    public function __construct(array $requiredFields){
        $this->requiredFields = $requiredFields;
    }

    public function __invoke(Request $request, Handler $handler) {
        $data = $request->getParsedBody() ?? [];
        $errors = [];

        foreach ($this->requiredFields as $field) {
            if ( !isset($data[$field]) && empty($data[$field]) ){
                $errors[] = "Field $field is required";
            }
        }

        if ( !empty($errors) ) {
            return $this->error(
                res:new Response(), 
                msg:"Failed validation",
                data:$errors
            );
        }

        // if everything is ok, keep going with any other middleware
        return $handler->handle($request);
    }
}


?>