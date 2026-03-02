<?php

namespace App\Database\Schemas;

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;

class UserSchema {

    public static function create() {
        Capsule::schema()->create('users', function (Blueprint $table) {        
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->timestamps();
        });

    }

}

?>