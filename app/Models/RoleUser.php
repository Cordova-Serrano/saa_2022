<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUser extends Model 
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_isid', 'role_id'
    ];

    public function users() 
    {
        return $this->belongsToMany(User::class);
        // return $this->belongsToMany('App/User');
    }
}