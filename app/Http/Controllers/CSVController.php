<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVImport;
use App\Models\Student;
use App\Models\Data;
use App\Models\Semester;
use App\Models\Career;
use App\Models\File;

class CSVController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = File::all();

            return DataTables::of($model)->toJson();
        }

        return view('csv.index');
    }

    public function store(Request $request)
    {
        $file_name_uns = pathinfo(request()->file('document')->getClientOriginalName(), PATHINFO_FILENAME); //obtiene el nombre del archivo sin extencion
        $file_name = explode(" ", $file_name_uns); //separa la cadena en arreglo
        $excel_array = Excel::toArray([], request()->file('document')); //convierte el archivo subido en arreglo
        //Si el nombre de archivo ya existe, se hace un update
        $data_excel = $excel_array[0]; //obtenemos las columnas
        //dd($excel_array, $data_excel);
        $lenght = count($data_excel);
        //Semester
        $semester_str = $file_name[1];
        //Semestre
        if (isset(Semester::where('semester', $semester_str)->first()->semester)) {
        } else {
            $semester = new Semester([
                "semester" => $semester_str,
            ]);
        }
        //Overwrite semester data
        if ($request->is_update == 1) {
            //Se actualiza la informacion de la base de datos,sobre el mismo archivo
            $students = Student::with('data')->get();
            //Recorremos toda la nueva informacion
            for ($i = 1; $i < $lenght; ++$i) {
                //Students
                $students[$i - 1]->uaslp_key = ($data_excel[$i])[0];
                $students[$i - 1]->large_key = ($data_excel[$i])[1];
                $students[$i - 1]->generation = ($data_excel[$i])[2];
                $students[$i - 1]->name = ($data_excel[$i])[3];
                $students[$i - 1]->career = ($data_excel[$i])[4];
                //Career
                if (isset(Career::where('name', $students[$i - 1]->career)->first()->name)) {
                } else {
                    $new_career = new Career([
                        "name" => $students[$i - 1]->career,
                    ]);
                    $new_career->save();
                }
                //Data
                dd($students, $data_excel,$students[$i - 1]->data[0]->id);
                //Obtener la tabla pivote que esta relacionada el estudiante para obtener la data correcta
                //Ya contamos con el semestre o file
                $data = Data::find(1);
                $students[$i - 1]->data[0]->status = ($data_excel[$i])[5];
                $students[$i - 1]->data[0]->creds_remaining = ($data_excel[$i])[6];
                $students[$i - 1]->data[0]->creds_per_semester = ($data_excel[$i])[7];
                $students[$i - 1]->data[0]->semesters_completed = ($data_excel[$i])[8];
                $students[$i - 1]->data[0]->percentage_progress = ($data_excel[$i])[9];
                $students[$i - 1]->data[0]->general_average = ($data_excel[$i])[10];
                $students[$i - 1]->data[0]->general_performance = ($data_excel[$i])[11];
                $students[$i - 1]->data[0]->app_average = ($data_excel[$i])[12];
                $students[$i - 1]->data[0]->subjects_approved = ($data_excel[$i])[13];
                $students[$i - 1]->data[0]->subjects_failed = ($data_excel[$i])[14];
                $students[$i - 1]->save();
            }
        } else {
            $file = new File([
                "name" => $file_name_uns,
            ]);
            $file->save();
            for ($i = 1; $i < $lenght; ++$i) {
                //Students
                $uaslp_key = ($data_excel[$i])[0];
                $large_key = ($data_excel[$i])[1];
                $generation = ($data_excel[$i])[2];
                $name = ($data_excel[$i])[3];
                $career = ($data_excel[$i])[4];
                //Career
                if (isset(Career::where('name', $career)->first()->name)) {
                } else {
                    $new_career = new Career([
                        "name" => $career,
                    ]);
                    $new_career->save();
                }
                //Data 
                $status = ($data_excel[$i])[5];
                $creds_remaining = ($data_excel[$i])[6];
                $creds_per_semester = ($data_excel[$i])[7];
                $semesters_completed = ($data_excel[$i])[8];
                $percentage_progress = ($data_excel[$i])[9];
                $general_average = ($data_excel[$i])[10];
                $general_performance = ($data_excel[$i])[11];
                $app_average = ($data_excel[$i])[12];
                $subjects_approved = ($data_excel[$i])[13];
                $subjects_failed = ($data_excel[$i])[14];
                if (isset(Student::where('uaslp_key', $uaslp_key)->first()->uaslp_key)) { //Busca si existe el alumno mediande su clave unica
                    $student = Student::where('uaslp_key', $uaslp_key)->first();
                    $student->fill([
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation,
                        "name" => $name,
                        "career" => $career,
                    ]);
                    $student->save();
                    $data = new Data([
                        "status" => $status,
                        "creds_remaining" => $creds_remaining,
                        "creds_per_semester" => $creds_per_semester,
                        "semesters_completed" => $semesters_completed,
                        "percentage_progress" => $percentage_progress,
                        "general_average" => $general_average,
                        "general_performance" => $general_performance,
                        "app_average" => $app_average,
                        "subjects_approved" => $subjects_approved,
                        "subjects_failed" => $subjects_failed,
                    ]);
                    $semester->save();
                    $data->save();
                    $student->data()->attach($data->id, ['semester_id' => $semester->id, 'file_id' => $file->id]);
                } else {
                    $student = new Student([
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation,
                        "name" => $name,
                        "career" => $career,
                    ]);
                    $data = new Data([
                        "status" => $status,
                        "creds_remaining" => $creds_remaining,
                        "creds_per_semester" => $creds_per_semester,
                        "semesters_completed" => $semesters_completed,
                        "percentage_progress" => $percentage_progress,
                        "general_average" => $general_average,
                        "general_performance" => $general_performance,
                        "app_average" => $app_average,
                        "subjects_approved" => $subjects_approved,
                        "subjects_failed" => $subjects_failed,
                    ]);
                    $student->save();
                    $data->save();
                    $semester->save();
                    $student->data()->attach($data->id, ['semester_id' => $semester->id, 'file_id' => $file->id]);
                }
            }
        }
        return redirect()->route('csv.index')->with('success', 'El archivo csv ha sido importado con éxito.');
    }

    public function updateDoc(Request $request)
    {
        if ($request->ajax()) {
            $file_name_uns = pathinfo($request->filename, PATHINFO_FILENAME); //obtiene el nombre del archivo sin extencion
            $file_name = explode(" ", $file_name_uns); //separa la cadena en arreglo
            //Revisar si existe un archivo con el mismo nombre
            $file = File::where('name', '=', $file_name_uns)->first();
            //Revisar si existe un semestre
            $semester = Semester::where('semester', '=', $file_name)->first();
            if ($file) {
                //Existe el archivo
                $response = 1;
            } else {
                //No existe, no sobrescribir
                $response = 0;
            }

            return $response;
        }
    }
}
