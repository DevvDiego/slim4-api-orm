<?php

namespace App\Database\Schemas;

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;

class CustomerSchema {

    public static function create() {
        Capsule::schema()->create('tickets', function (Blueprint $table) {        
            $table->id();
            $table->string("company_name");
            $table->string("contact_person");
            $table->timestamps();
        });

    }

}

?>