<?php

namespace App\Database\Schemas;

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;

class TicketSchema {

    public static function create() {
        Capsule::schema()->create('tickets', function (Blueprint $table) {        
            $table->id();
            $table->foreignId("user_id");
            $table->foreignId("customer_id");
            $table->decimal("value", 10, 2);
            $table->enum("status", ["done", "processing", "closed"])->default("processing");
            $table->timestamps();
        });

    }

}

?>