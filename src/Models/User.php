<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = "users";
    protected $primaryKey = "user_id";
    public $timestamps = false; // use if sql has columns created_at/updated_at

    protected $hidden = ["password_hash"];

    // enable massive fill of fields
    protected $fillable = ["name", "email", "role", "password_hash"];
}

?>