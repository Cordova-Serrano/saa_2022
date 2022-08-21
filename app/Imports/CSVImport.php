<?php

namespace App\Imports;

use App\Models\CSV;
use App\Models\Student;
use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
class CSVImport implements
    ToModel,
    WithHeadingRow,
    WithCalculatedFormulas
{
    use Importable, SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        //dd($row);
        /*if (isset(Student::where('uaslp_key', $row['cve_uaslp'])->first()->uaslp_key)) {
            //Ya existe el alumno
            //Actualizamos al alumno
            Student::where('uaslp_key', $row['cve_uaslp'])
                ->update([
                    "uaslp_key" => $row['cve_uaslp'],
                    "large_key" => $row['cve_larga'],
                    "generation" => $row['generación'],
                    "name" => $row['nombre'],
                    "career" => $row['carrera'],
                ]);
            //Data alumno
        } else {
            //No existe el alumno en la Base de datos
            //Creamos un nuevo alumno a la base de datos
            return new Student([
                "uaslp_key" => $row['cve_uaslp'],
                "large_key" => $row['cve_larga'],
                "generation" => $row['generación'],
                "name" => $row['nombre'],
                "career" => $row['carrera'],
            ]);
        }
        //Continuamos con su informacion
        new Data([
            "status" => $row['situación'],
            "creds_remaining" => $row['cred_por_cursar'],
            "creds_per_semester" => $row['cred_por_semestre'],
            "semesters_completed" => $row['semestres_cursados'],
            "percentage_progress" => $row['porcentaje_avance'],
            "general_average" => $row['promedio_general'],
            "general_performance" => $row['rendimiento_general'],
            "app_average" => $row['promedio_aprobatorio'],
            "subjects_approved" => $row['materias_aprobadas'],
            "subjects_failed" => $row['materias_reprobadas'],
        ]);*/
        $csv = new CSV();
        $csv->uaslp_key = $row['cve_uaslp'];
        $csv->large_key = $row['cve_larga'];
        $csv->generation = $row['generacion'];
        $csv->name = $row['nombre'];
        $csv->career = $row['carrera'];
        $csv->status = $row['situacion'];
        $csv->creds_remaining = $row['cred_por_cursar'];
        $csv->creds_per_semester = $row['cred_por_semestre'];
        $csv->semesters_completed = $row['semestres_cursados'];
        $csv->percentage_progress = $row['porcentaje_avance'];
        $csv->general_average = $row['rendimiento_general'];
        $csv->general_performance = $row['rendimiento_general'];
        $csv->app_average = $row['promedio_aprobatorio'];
        $csv->subjects_approved = $row['materias_aprobadas'];
        $csv->subjects_failed = $row['materias_reprobadas'];
        $csv->large_key = $row['cve_larga'];
        //dd($csv);
        return $csv;

        /*return new CSV([
            "uaslp_key" => $row['cve_uaslp'],
            "large_key" => $row['cve_larga'],
            "generation" => $row['generacion'],
            "name" => $row['nombre'],
            "career" => $row['carrera'],
            "status" => $row['situacion'],
            "creds_remaining" => $row['cred_por_cursar'],
            "creds_per_semester" => $row[''],
            "semesters_completed" => $row['semestres_cursados'],
            "percentage_progress" => $row['porcentaje_avance'],
            "general_average" => $row['promedio_general'],
            "general_performance" => $row['rendimiento_general'],
            "app_average" => $row['promedio_aprobatorio'],
            "subjects_approved" => $row['materias_aprobadas'],
            "subjects_failed" => $row['materias_reprobadas'],
        ]);*/
    }

    public function rules(): array
    {
        return [
            '*.codigo'              => ['bail', 'required', 'min:1', 'max:20'],
            '*.nombre_comercial'    => ['bail', 'required', 'max:255'],
            '*.nombre_generico'     => ['bail', 'required', 'max:255'],
            '*.presentacion'        => ['bail', 'required', 'numeric'],
            '*.unidades'            => ['bail', 'required', 'numeric'],
            '*.precio_de_compra'    => ['bail', 'required', 'numeric'],
            '*.precio_de_venta'     => ['bail', 'required', 'numeric'],
            '*.descuento'           => ['bail', 'required', 'numeric', 'between:0,100'],
            '*.minimo'              => ['bail', 'required', 'numeric'],
            '*.maximo'              => ['bail', 'required', 'numeric'],
            '*.punto_de_reorden'    => ['bail', 'required', 'numeric'],
            '*.expira'              => ['required', 'numeric'],
            '*.aplica_iva'          => ['required', 'numeric'],
            '*.clasificacion'       => ['bail', 'required', 'exists:classifications,name']
        ];
    }
}
