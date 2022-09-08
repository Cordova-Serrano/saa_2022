<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Career;

class GraphsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
        $gen = Student::orderBy('generation', 'asc')->select('generation')->distinct()->get();
        //$generation = DataTables::of($gen)->toJson();
        return view('graphs.index', compact('semesters', 'careers','gen'));
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
