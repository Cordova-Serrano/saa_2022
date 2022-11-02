<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uaslp_key', 'large_key', 'generation', 'name', 'career_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function data()
    {
        return $this->belongsToMany(Data::class, 'student_data_semester', 'student_id', 'data_id')
            ->withPivot('semester_id','file_id');
    }

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
