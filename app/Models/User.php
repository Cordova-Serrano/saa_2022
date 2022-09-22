<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $fillable = [
        'name', 'email', 'username', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
    ];

    public function authorizeRoles($roles) 
    {
        abort_unless($this->hasAnyRole($roles), 401);

        return true;
    }

    public function hasAnyRole($roles) {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole($role) 
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }

        return false;
    }

    public function roles() 
    {
        return $this->belongsToMany(Role::class);
        // return $this->belongsToMany('App/Role');
    }
}