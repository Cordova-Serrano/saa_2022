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
use Illuminate\Support\Facades\DB;
class CSVController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
        $doc_info = pathinfo(request()->file('document')->getClientOriginalName());
        $extension = $doc_info['extension'];
        //  dd($doc_info);
        $file_name_uns = $doc_info['filename']; //pathinfo(request()->file('document')->getClientOriginalName(), PATHINFO_FILENAME); //obtiene el nombre del archivo sin extencion
        $file_name = explode(" ", $file_name_uns); //separa la cadena en arreglo
        $excel_array = Excel::toArray([], request()->file('document')); //convierte el archivo subido en arreglo
        //Si el nombre de archivo ya existe, se hace un update
        $data_excel = $excel_array[0]; //obtenemos las columnas
        //dd($excel_array, $data_excel);
        $lenght = count($data_excel);
        if($extension != 'csv')
            $lenght = $lenght-1;
        //Semester
        $semester_str = $file_name[1];
        //Semestre
        
        if (isset(Semester::where('semester', $semester_str)->first()->semester)) {
            $semester = Semester::where('semester', $semester_str)->first();

        } 
        
        else {
            $semester = new Semester([
                "semester" => $semester_str,
            ]);
        }
        $error_array_s = array();
            $error_array_d = array();
        //Overwrite semester data
        if ($request->is_update == 1) {
            
            $flag = 0;
            for ($i = 1; $i < count($data_excel); ++$i){//loop to read registers of file

                $register_excel = $data_excel[$i];//get n register
                $t_student = Student::where('uaslp_key', $register_excel[0])->first();
                if(isset($t_student)){// if student exist, update student
                    $t_student->large_key = $register_excel[1];
                    $t_student->generation = $register_excel[2];
                    $t_student->name = $register_excel[3];
                    $t_student->career = $register_excel[4];
                    $t_student->update();
                }
                else {//create student register
                    $t_student = Student::create([
                        "uaslp_key" => $register_excel[0],
                        "large_key" => $register_excel[1],
                        "generation" => $register_excel[2],
                        "name" => $register_excel[3],
                        "career" => $register_excel[4],
                    ]);
                     $t_student->save();
                     $flag = 1;
                }

                if($flag == 0){//if student is updated
                    $t_student = $t_student->data[0];
                     //Data 
                     $t_student->status = $register_excel[5];
                    $t_student->creds_remaining = check_value($register_excel[6]);
                    $t_student->creds_per_semester = check_value($register_excel[7]);
                    $t_student->semesters_completed = $register_excel[8];
                    $t_student->percentage_progress = check_value($register_excel[9]);
                    $t_student->general_average = check_value($register_excel[10]);
                    $t_student->general_performance = check_value($register_excel[11]);
                    $t_student->app_average = check_value($register_excel[12]);
                    $t_student->subjects_approved = check_value($register_excel[13]);
                    $t_student->subjects_failed = check_value($register_excel[14]);
                    
                    $t_student->update();
    
                }
                else{//if student is created
                    $t_data = Data::create([
                        'status' => $register_excel[5],
                        'creds_remaining' => check_value($register_excel[6]),
                        'creds_per_semester' => check_value($register_excel[7]), 
                        'semesters_completed' => $register_excel[8],
                        'percentage_progress' => check_value($register_excel[9]),
                        'general_average' => check_value($register_excel[10]),
                        'general_performance' =>check_value($register_excel[11]),
                        'app_average'=>check_value($register_excel[12]),
                        'subjects_approved' => check_value($register_excel[13]),
                        'subjects_failed' => check_value($register_excel[14]),
                    ]);
                    $t_data->save();
                    $ste = 'ACC '.$semester->semester;
                    $info_file = File::where('name', $ste)->first();
                    $t_student->data()->attach($t_data->id, ['semester_id' => $semester->id, 'file_id' => $info_file->id]);
                }
            }
        } else {//if upload a new semester
            $file = new File([//create register in File
                "name" => $file_name_uns,
            ]);
            $file->save(); //save it
            for ($i = 1; $i < $lenght; ++$i) {
                $register_excel = $data_excel[$i];//get n register
                //Students
                $uaslp_key = ($data_excel[$i])[0];
                $large_key = ($data_excel[$i])[1];
                //$generation = ($data_excel[$i])[2];
                $name = ($data_excel[$i])[3];
                //$career = ($data_excel[$i])[4];
                //data obtained from large key
                $generation_lk = substr($large_key,0,4);//generation
                //$s_lk = substr($large_key,4,1);//sex
                $c_key = substr($large_key,5,2);
                if(strcmp( $c_key,"15")) $career_lk = "INGENIERÍA EN COMPUTACIÓN";//career
                else
                if(strcmp($c_key,"23")) $career_lk = "INGENIERÍA EN SISTEMAS INTELIGENTES";//career
                // dd(substr($large_key,5,2));
                $ingreso_lk = substr($large_key,7,1);//way of entry
                // dd(intval(substr($large_key,7,2)));

                //Career
                if (isset(Career::where('name', $career_lk)->first()->name)) {
                } else {
                    $new_career = new Career([
                        "name" => $career_lk,
                    ]);
                    $new_career->save();
                }
                
                if (isset(Student::where('uaslp_key', $uaslp_key)->first()->uaslp_key)) { //Busca si existe el alumno mediande su clave unica
                    $student = Student::where('uaslp_key', $uaslp_key)->first();
                    $student->fill([
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation_lk,
                        "name" => $name,
                        "career" => $career_lk,
                        "type" => intval($ingreso_lk),
                    ]);
                    $student->save();
                    $data = new Data([
                        'status' => $register_excel[5],
                        'creds_remaining' => check_value($register_excel[6]),
                        'creds_per_semester' => check_value($register_excel[7]), 
                        'semesters_completed' => $register_excel[8],
                        'percentage_progress' => check_value($register_excel[9]),
                        'general_average' => check_value($register_excel[10]),
                        'general_performance' =>check_value($register_excel[11]),
                        'app_average'=>check_value($register_excel[12]),
                        'subjects_approved' => check_value($register_excel[13]),
                        'subjects_failed' => check_value($register_excel[14]),
                    ]);
                    
                    try {
                        $semester->save();
                        $data->save();
                        $student->data()->attach($data->id, ['semester_id' => $semester->id, 'file_id' => $file->id]);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                } else {
                    $student = new Student([
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation_lk,
                        "name" => $name,
                        "career" => $career_lk,
                        "type" => $ingreso_lk,
                    ]);
                    $data = new Data([
                        'status' => $register_excel[5],
                        'creds_remaining' => check_value($register_excel[6]),
                        'creds_per_semester' => check_value($register_excel[7]), 
                        'semesters_completed' => $register_excel[8],
                        'percentage_progress' => check_value($register_excel[9]),
                        'general_average' => check_value($register_excel[10]),
                        'general_performance' =>check_value($register_excel[11]),
                        'app_average'=>check_value($register_excel[12]),
                        'subjects_approved' => check_value($register_excel[13]),
                        'subjects_failed' => check_value($register_excel[14]),
                    ]);
                    $fs=0;
                    $semester->save();
                    try {//intenta guardar registro de students
                        $student->save();
                        
                    } catch (\Throwable $th) {
                        $fs = 1;//cuando hay error en students cambia la bandera para no guardar su data
                        array_push($error_array_s,$i + 1); //deteccion de errores en students
                    }
                    try {
                        if($fs == 0){//en caso de que los datos de alumno sean correctos
                            $data->save(); 
                            $student->data()->attach($data->id, ['semester_id' => $semester->id, 'file_id' => $file->id]);
                        }
                        
                    } catch (\Throwable $th) {//si hay error en data, borra el registro de estudiante
                        DB::table('students')->
                        orderBy('id', 'desc')->limit(1)->delete();
                        array_push($error_array_d,$i + 1);//deteccion de errores en data
                    }

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
function check_value($v){
    if($v != null)
        $cr = $v;
    else
        $cr = 0;
    return $cr;
    } 
