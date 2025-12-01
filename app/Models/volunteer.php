<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    //Table name
    protected $table = 'volunteer';
    public $timestamps = false; 
    // Specify the fields 
    protected $fillable = [
        'user_id',  
        'responsibility',  
        'employee_id',
    ];

    /**
     * Get the user that owns the volunteer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
