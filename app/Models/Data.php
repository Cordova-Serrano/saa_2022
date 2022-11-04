<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Data extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status', 'creds_remaining', 'creds_per_semester', 'semesters_completed', 'percentage_progress', 'general_average', 
        'general_performance', 'app_average', 'subjects_approved', 'subjects_failed'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function student()
    {
        return $this->belongsToMany(Student::class, 'student_data_semester', 'data_id', 'student_id')
            ->withPivot('semester_id');
    }
}
