<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    public function loadGeneration(Request $request)
    {
        if ($request->ajax()) {
            $data_generation = Student::join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
                ->where('student_data_semester.semester_id', '=', $request->semester_id)
                ->where('students.career', '=',$request->career)
                ->where('students.generation', '=',$request->generation)
                ->join('data', 'data.id', '=', 'student_data_semester.data_id')->get();
                
            return $data_generation;
        }
    }
}
