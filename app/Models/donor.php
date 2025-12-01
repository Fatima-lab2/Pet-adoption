<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'donor';
    public $timestamps = false; 
    // Specify the fields 
    protected $fillable = [
        'user_id', 
        'previous_donation_number',    
    ];

    /**
     * Get the user that owns the donor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
