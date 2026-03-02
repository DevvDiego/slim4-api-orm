<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    // Eloquent looks for plural user (users) by default 
    //protected $table = "users";

    // Eloquent looks for "id" by default
    //protected $primaryKey = "id";

    // Eloquent by default sets timestamps by default
    //public $timestamps = true; 

    protected $hidden = ["password"];

    protected $fillable = [
        "name", 
        "email", 
        "role", 
        "password"
    ];
}

?>