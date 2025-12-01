<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'employee';
    public $timestamps = false; 
    // Specify the fields 
    protected $fillable = [
        'user_id',  
        'responsibility',
        'type_of_work',
        'department_id',
    ];

    /**
     * Get the user that owns the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
