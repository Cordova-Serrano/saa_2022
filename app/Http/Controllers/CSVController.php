<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVImport;
use App\Models\Student;
use App\Models\Data;
use App\Models\Semester;
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
