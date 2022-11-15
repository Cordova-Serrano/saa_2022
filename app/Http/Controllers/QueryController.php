<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class QueryController extends Controller
{
    public function loadQuery(Request $request)
    {     
        //   $query = DB::table('students')->join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
        //  ->join('data', 'data.id', '=', 'student_data_semester.data_id')
        //  ->join('careers','careers.id', "=", "students.career_id")
        //  ->select('students.*','careers.name as career','data.*','student_data_semester.*')
        //  ->get();

        //  dd($query);
        if ($request->ajax()) {
            $query = DB::table('students')
        ->join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
        ->join('data', 'data.id', '=', 'student_data_semester.data_id')
        ->join('careers','careers.id', "=", "students.career_id")
        ->select('students.*','careers.name as career','data.*','student_data_semester.*');
            
            if($request->semester_id)//check if specify semester
                $query->where('student_data_semester.semester_id', '=', $request->semester_id);
            if($request->career)//check if specify career
                $query->where('careers.name', '=',$request->career);
            if($request->generation)//check if specify generation
                $query->where('student.generation', '=',$request->generation);
            return $query->get();
        }
    }
}
