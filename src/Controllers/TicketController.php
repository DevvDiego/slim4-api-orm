<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Traits\ResponseTrait;

class TicketController {
    use ResponseTrait;

    // nothing here, just sets the dependency of the db
    public function __construct(Capsule $db) {}

    public function showTicket(Request $request, Response $response, $args) {
        $ticket = \App\Models\Ticket::query()->find($args["id"]);

        if (!$ticket) {
            return $this->error($response, "Ticket no encontrado", 404);
        }

        return $this->success($response, $ticket);
    }

}

?>