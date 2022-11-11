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

        return view('csv.import');
    }

    public function store(Request $request)
    {
        $doc_info = pathinfo(request()->file('file')->getClientOriginalName());
        $extension = $doc_info['extension'];
        $file_name_uns = $doc_info['filename']; //pathinfo(request()->file('document')->getClientOriginalName(), PATHINFO_FILENAME); //obtiene el nombre del archivo sin extencion
        $file_name = explode(" ", $file_name_uns); //separa la cadena en arreglo
        $excel_array = Excel::toArray([], request()->file('file')); //convierte el archivo subido en arreglo
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
        $error_array_s = array();//arreglo para deteccion de errores de estudiantes
        $error_array_d = array();//arreglo para deteccion de errores de data
        //Sobreescritura de semestre
        if ($request->is_update == 1) {//verifica si será proceso de sobreescritura o escritura
            $flag = 0;
            for ($i = 1; $i < count($data_excel); ++$i){//ciclo para leer la informacion del archivo
                $register_excel = $data_excel[$i];//obtiene la informacion del registro
                $career = Career::where('name', $register_excel[4])->first();//obtiene la carrera
                $large_key = $register_excel[1];//obtiene la clave larga
                $c_key = substr($large_key,5,2);//obtiene la clave de carrera
                if($c_key=="15")//clave 15 de ingenieria en computacion
                $career_lk = "INGENIERÍA EN COMPUTACIÓN";
                else
                    if($c_key=="23") //clave 23 de sistemas inteligentes
                    $career_lk = "INGENIERÍA EN SISTEMAS INTELIGENTES";
                    else
                        if($c_key=="14")//clave 14 de informatica
                        $career_lk = "INGENIERÍA EN INFORMÁTICA";

                //Carrera
                $new_career = Career::where('name', $career_lk)->first();//consulta la carrera en la tabla carreras
                if (!isset($new_career->name)) {//si no hay un registro coincidente, crea el nuevo registro en la tabla career
                    $new_career = new Career([
                        "name" => $career_lk,
                    ]);
                    $new_career->save();//guarda registro de carrera nueva
                } 

                $t_student = Student::where('uaslp_key', $register_excel[0])->first();//obtiene el estudiante correspondiente 
                if(isset($t_student)){// si existe el estudiante, actualiza su informacion de tabla student
                    $t_student->large_key = $register_excel[1];//actualiza clave larga
                    $t_student->generation = $register_excel[2];//actualiza generacion
                    $t_student->name = $register_excel[3];//actualiza nombre
                    $t_student->career_id = $new_career->id ;//actualiza carrera id
                    $t_student->update();//actualiza
                }
                else {//crea nuevo registro de estudiante
                    $t_student = Student::create([
                        "uaslp_key" => $register_excel[0],//guarda clave uaslp
                        "large_key" => $register_excel[1],//guarda clave larga
                        "generation" => $register_excel[2],//guarda generacion
                        "name" => $register_excel[3],//guarda nombre
                        "career_id" => $new_career->id,//guarda carrera
                    ]);
                     $t_student->save();//guarda registro de estudiante en student
                     $flag = 1;//bandera para conocer si se actualizo estudiante o se creó un nuevo registro
                }

                if($flag == 0){//si se actualizo estudiante, queire decir que se actualiza su registro de data
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
                else{//si se creo estudiante quiere decir que es un nuevo registro de data
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
                    $t_data->save();//se guarda registro de data
                    $ste = 'ACC '.$semester->semester;
                    $info_file = File::where('name', $ste)->first();
                    $t_student->data()->attach($t_data->id, ['semester_id' => $semester->id, 'file_id' => $info_file->id]);
                }
            }
        } else {//si se sube un nuevo semestre
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
                    $career_lk = null;//no se consiguio una clave de carrera correcta
                }

                
                if (!isset($new_career->name) && $career_lk) {//si new_career no esta seteado y career_lk es correcto
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
                        'semesters_completed' => $register_excel[8],
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
                        "type" => $ingreso_lk,
                    ]);
                    $data = new Data([//guarda registro de data de semestre
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
