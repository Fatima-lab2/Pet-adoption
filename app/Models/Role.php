<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
//the Role model here define its relationship with the User model.
class Role extends Model
{
    // Table name
    protected $table = 'role'; 
    // Tell Laravel the primary key is role_id
    protected $primaryKey = 'role_id'; 
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_name',
        'description',
        'added_by_admin'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
   
}
