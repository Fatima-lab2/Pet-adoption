<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adopter extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'adopter';
    public $timestamps = false; 
    // Specify the fields 
    protected $fillable = [
        'user_id',  
        'previous_adoption_number',
    ];

    /**
     * Get the user that owns the adopter.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
