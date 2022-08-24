<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Career;
use DataTables;

class ConsultController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = Student::join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
                ->join('data', 'data.id', '=', 'student_data_semester.data_id')
                ->get();

            return DataTables::of($model)->toJson();
        }
        //Recuperamos los semestres y carreras
        $semesters = Semester::get();
        $careers = Career::get();
        return view('consult.index', compact('semesters', 'careers'));
    }

    public function loadSemester(Request $request)
    {
        if ($request->ajax()) {
            $data_semester = Student::join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
                ->where('student_data_semester.semester_id', '=', $request->semester_id)
                ->join('data', 'data.id', '=', 'student_data_semester.data_id')->get();
            return $data_semester;
        }
    }

    public function test(Request $request)
    {
        if ($request->ajax()) {
            $model = Student::join('student_data_semester', 'student_data_semester.student_id', '=', 'students.id')
                ->join('data', 'data.id', '=', 'student_data_semester.data_id')
                ->get();

            return DataTables::of($model)->toJson();
        }
        //Recuperamos los semestres y carreras
        $semesters = Semester::get();
        $careers = Career::get();
        return view('test.test', compact('semesters', 'careers'));
    }
}
