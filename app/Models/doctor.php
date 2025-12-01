<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    //Table name
    protected $table = 'doctor';
    public $timestamps = false; 
    // Specify the fields 
    protected $fillable = [
        'user_id',    
        'experience_year',  
        'specialization',
        'started_date', 
        
    ];

    /**
     * Get the user that owns the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
