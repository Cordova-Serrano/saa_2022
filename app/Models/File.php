<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_at'];
    protected $hidden = ['updated_at', 'updated_by'];
}
