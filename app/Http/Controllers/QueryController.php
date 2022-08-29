<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class QueryController extends Controller
{
    public function loadQuery(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('students')->join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
            ->join('data', 'data.id', '=', 'student_data_semester.data_id');

            if($request->semester_id)//check if specify semester
                $query->where('student_data_semester.semester_id', '=', $request->semester_id);
            if($request->career)//check if specify career
                $query->where('students.career', '=',$request->career);
            if($request->generation)//check if specify generation
                $query->where('students.generation', '=',$request->generation);
            
            return $query->get();
        }
    }
}
