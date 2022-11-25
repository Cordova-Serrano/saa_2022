<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    /** 
     * @desc Regresa la vista de importar archivo
    
    */
    public function import()
    {
        return view('csv.import');
    }
    public function store(Request $request)
    {
        $doc_info = pathinfo(request()->file('file')->getClientOriginalName());
        $extension = $doc_info['extension'];
        $file_name_uns = $doc_info['filename']; 
        $file_name = explode(" ", $file_name_uns); //separa la cadena en arreglo
        $excel_array = Excel::toArray([], request()->file('file')); //convierte el archivo subido en arreglo
        //Si el nombre de archivo ya existe, se hace un update
        $data_excel = $excel_array[0]; //obtenemos las columnas
        $no_process = 0;//variable para verificar que el excel contiene el numero completo de columnas deacuerdo al formato
        //dd($data_excel);
        if(count($data_excel[0]) != 15) $no_process = 1;//si no tiene las columnas suficientes
        else{
            $lenght = count($data_excel);
            if($extension != 'csv')
                $lenght = $lenght-1;
            //Semester
            $semester_str = $file_name[1];
            //Semestre
            if (isset(Semester::where('semester', $semester_str)->first()->semester)) {
                $semester = Semester::where('semester', $semester_str)->first();
                $dss = DB::table('student_data_semester')->where('semester_id', $semester->id);
                $dss->delete();     
            } 

            else {
                $semester = new Semester([
                    "semester" => $semester_str,
                ]);
            }
            $error_array_s = array();//arreglo para deteccion de errores de estudiantes
            $error_array_d = array();//arreglo para deteccion de errores de data
        }
        //Sobreescritura de semestre
        if ($request->is_update == 1 && $no_process == 0) {//verifica si será proceso de sobreescritura o escritura
            for ($i = 1; $i < count($data_excel); ++$i){//ciclo para leer la informacion del archivo
                $register_excel = $data_excel[$i];//obtiene la informacion del registro
                $uaslp_key = $register_excel[0];//clave uaslp
                $large_key = $register_excel[1];//obtiene la clave larga
                $generation_lk = substr($large_key,0,4);//generacion
                $name = $register_excel[3];
                $c_key = substr($large_key,5,2);//obtiene la clave de carrera
                $career_lk = null;
                if($c_key=="15" || $c_key=="47")//clave 15 de ingenieria en computacion
                $career_lk = "INGENIERÍA EN COMPUTACIÓN";
                else
                    if($c_key=="23" || $c_key=="54") //clave 23 de sistemas inteligentes
                    $career_lk = "INGENIERÍA EN SISTEMAS INTELIGENTES";
                    else
                        if($c_key=="14")//clave 14 de informatica
                        $career_lk = "INGENIERÍA EN INFORMÁTICA";

                //Carrera
                $ingreso_lk = substr($large_key,7,1);//manera de ingreso a la facultad
                try {//intenta buscar la carrera
                    $new_career = Career::where('name', $career_lk)->first();//consulta carrera
                } catch (\Throwable $th) {
                    //en caso de que no consiga carrera quiere decir que no está bien la clave larga
                    if($career_lk != ("15" || "23" || "14" || "47" || "54"))
                    $career_lk = null;//no se consiguio una clave de carrera correcta
                    $new_career = null;
                }

                
                if (!isset($new_career->name) && $career_lk == ("15" || "23" || "14")) {//si new_career no esta seteado y career_lk es correcto
                    $new_career = new Career([
                        "name" => $career_lk,
                    ]);
                    $new_career->save();//guarda el registro de carrera en caso de que la clave se haya detectado de manera correcta
                }
                $di = 0;//di por datos incorrectos, bandera para detectar datos incorrectos
                $t_student = Student::where('uaslp_key', $uaslp_key)->first();//obtiene el estudiante correspondiente 
                if(isset($t_student)){// si existe el estudiante, actualiza su informacion de tabla student
                    $t_student->large_key = $large_key;//actualiza clave larga
                    $t_student->generation = $generation_lk;//actualiza generacion
                    $t_student->name = $name;//actualiza nombre
                    $t_student->career_id = $new_career->id ;//actualiza carrera id
                    $t_student->type = intval($ingreso_lk);
                    try {
                        $t_student->update();//actualiza
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                else {//crea nuevo registro de estudiante
                    $t_student = new Student([
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation_lk,
                        "name" => $name,
                        "career_id" => $new_career->id,
                        "type" => intval($ingreso_lk),
                    ]);
                    try {
                        $t_student->save();//guarda registro de estudiante en student
                    } catch (\Throwable $th) {
                        //throw $th;
                        $di = 1;//si no se pudo guardar un nuevo estudiante, asigna di a 1
                    }
                } 
                    $t_data = Data::create([//crea esquema de data
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
                        if($di == 0){//solo intenta guardar data en caso de que alumno sea correcto
                            $t_data->save();//se guarda registro de data
                            $ste = 'ACC '.$semester->semester;
                            $info_file = File::where('name', $ste)->first();
                            $t_student->data()->attach($t_data->id, ['semester_id' => $semester->id, 'file_id' => $info_file->id]);//code...
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                        array_push($error_array_d,$i + 1);//deteccion de errores en data
                    }
    
            }
        } else 
        if ($no_process == 0) 
        {//si se sube un nuevo semestre
            $file = new File([//se crea registro de FIle
                "name" => $file_name_uns,
            ]);
            $file->save(); //se guarda registro
            for ($i = 1; $i < $lenght; ++$i) {
                $register_excel = $data_excel[$i];//get n register
                //Students
                $uaslp_key = ($data_excel[$i])[0];//clave uaslp
                $large_key = ($data_excel[$i])[1];//clave larga
                $name = ($data_excel[$i])[3];//nombre
                $generation_lk = substr($large_key,0,4);//generacion
                $c_key = substr($large_key,5,2);//clave de clave larga
                $career_lk = null;
                if($c_key=="15")//clave 15 de ingenieria en computacion
                $career_lk = "INGENIERÍA EN COMPUTACIÓN";
                else
                    if($c_key=="23") //clave 23 de sistemas inteligentes
                    $career_lk = "INGENIERÍA EN SISTEMAS INTELIGENTES";
                    else
                        if($c_key=="14")//clave 14 de informatica
                        $career_lk = "INGENIERÍA EN INFORMÁTICA";
                $ingreso_lk = substr($large_key,7,1);//manera de ingreso a la facultad
                try {//intenta buscar la carrera
                    $new_career = Career::where('name', $career_lk)->first();//consulta carrera
                } catch (\Throwable $th) {
                    //en caso de que no consiga carrera quiere decir que no está bien la clave larga
                    if($career_lk != ("15" || "23" || "14"))
                    $career_lk = null;//no se consiguio una clave de carrera correcta
                    $new_career = null;
                }

                
                if (!isset($new_career->name) && $career_lk == ("15" || "23" || "14")) {//si new_career no esta seteado y career_lk es correcto
                    $new_career = new Career([
                        "name" => $career_lk,
                    ]);
                    $new_career->save();//guarda el registro de carrera en caso de que la clave se haya detectado de manera correcta
                }
                
                if (isset(Student::where('uaslp_key', $uaslp_key)->first()->uaslp_key) ) { //Busca si existe el alumno mediande su clave unica
                    $student = Student::where('uaslp_key', $uaslp_key)->first();//obtiene el registro del estudiante
                    $student->fill([//rellena el esquema para student
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation_lk,
                        "name" => $name,
                        "career_id" => $new_career->id,
                        "type" => intval($ingreso_lk),
                    ]);
                    
                    $data = new Data([//crea el esquema para data
                        'status' => $register_excel[5],
                        'creds_remaining' => check_value($register_excel[6]),
                        'creds_per_semester' => check_value($register_excel[7]),
                        'semesters_completed' => check_value($register_excel[8]),
                        'percentage_progress' => check_value($register_excel[9]),
                        'general_average' => check_value($register_excel[10]),
                        'general_performance' =>check_value($register_excel[11]),
                        'app_average'=>check_value($register_excel[12]),
                        'subjects_approved' => check_value($register_excel[13]),
                        'subjects_failed' => check_value($register_excel[14]),
                    ]);
                    
                    try {//intenta guardar semestre, data de alumno y student
                        $student->save();//guarda estudiante
                        $semester->save();//guarda semestre
                        $data->save();//guarda data
                        $student->data()->attach($data->id, ['semester_id' => $semester->id, 'file_id' => $file->id]);
                    } catch (\Throwable $th) {
                        //
                    }
                } else {//si no hay alumno en registro, crea uno nuevo
                    if ($new_career)//comprueba que new career no sea null
                    $student = new Student([
                        "uaslp_key" => $uaslp_key,
                        "large_key" => $large_key,
                        "generation" => $generation_lk,
                        "name" => $name,
                        "career_id" => $new_career->id,
                        "type" => intval($ingreso_lk),
                    ]);
                    $data = new Data([//guarda registro de data de semestre
                        'status' => $register_excel[5],
                        'creds_remaining' => check_value($register_excel[6]),
                        'creds_per_semester' => check_value($register_excel[7]), 
                        'semesters_completed' => check_value($register_excel[8]),
                        'percentage_progress' => check_value($register_excel[9]),
                        'general_average' => check_value($register_excel[10]),
                        'general_performance' =>check_value($register_excel[11]),
                        'app_average'=>check_value($register_excel[12]),
                        'subjects_approved' => check_value($register_excel[13]),
                        'subjects_failed' => check_value($register_excel[14]),
                    ]);
                    $fs=0;//bandera para verificar si es creacion o actualizacion
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
function check_value($v){//evalua el valor recibido como parametro
    if($v != null)//en caso de que sea null, lo asigna a la variable 
        $cr = $v;
    else//si es null, lo asigna como 0
        $cr = 0;
    return $cr;
    } 
