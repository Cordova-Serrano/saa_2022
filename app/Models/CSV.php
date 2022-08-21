<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSV extends Model
{
    use HasFactory;

    protected $fillable = [
        'uaslp_key', 'large_key', 'generation', 'name', 'career', 'status', 'creds_remaining', 'creds_per_semester', 
        'semesters_completed', 'percentage_progress', 'general_average', 'general_performance', 'app_average', 'subjects_approved',
        'subjects_failed'
    ];
}
