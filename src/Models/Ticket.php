<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $table = "tickets";
    protected $primaryKey = "ticket_id";
    protected $fillable = [
        "user_id", 
        "customer_id",
        "title",
        "value",
        "status"
    ]; //enable massive fill of fields

    public $timestamps = false; // use if sql has columns created_at/updated_at

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}


?>