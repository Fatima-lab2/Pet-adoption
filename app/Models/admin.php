<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    //table name
    protected $table = 'admin';
    public $timestamps = false; 
    // Specify the fields 
    protected $fillable = [
        'user_id',
        'details'
    ];

    /**
     * Get the user that owns the admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
