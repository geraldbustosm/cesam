<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Inserts release-group
        DB::table('grupo_alta')->insert(['descripcion' => 'Abandono', 'created_at' => Carbon::now()]);
        DB::table('grupo_alta')->insert(['descripcion' => 'Fallecimiento', 'created_at' => Carbon::now()]);
        DB::table('grupo_alta')->insert(['descripcion' => 'Terapéutica', 'created_at' => Carbon::now()]);
        DB::table('grupo_alta')->insert(['descripcion' => 'Traslado', 'created_at' => Carbon::now()]);

        // Inserts release
        DB::table('alta')->insert(['descripcion' => 'Alta Administrativa Por Abandono', 'grupo_id' => 1, 'created_at' => Carbon::now()]);
        DB::table('alta')->insert(['descripcion' => 'Alta Administrativa Por Fallecimiento', 'grupo_id' => 2, 'created_at' => Carbon::now()]);
        DB::table('alta')->insert(['descripcion' => 'Alta Contra Referido', 'grupo_id' => 3, 'created_at' => Carbon::now()]);
        DB::table('alta')->insert(['descripcion' => 'Alta Terapéutica', 'grupo_id' => 3, 'created_at' => Carbon::now()]);
        DB::table('alta')->insert(['descripcion' => 'Alta Administrativa Por Traslado de domicilio', 'grupo_id' => 4, 'created_at' => Carbon::now()]);

        // Insert male and female
        DB::table('sexo')->insert(['descripcion' => 'Hombre', 'created_at' => Carbon::now()]);
        DB::table('sexo')->insert(['descripcion' => 'Mujer', 'created_at' => Carbon::now()]);

        // Insert types
        DB::table('tipo_prestacion')->insert(['descripcion' => 'GES', 'created_at' => Carbon::now()]);
        DB::table('tipo_prestacion')->insert(['descripcion' => 'PPV', 'created_at' => Carbon::now()]);

        // Insert sigges
        DB::table('sigges')->insert(['descripcion' => 'Depresión', 'created_at' => Carbon::now()]);
        DB::table('sigges')->insert(['descripcion' => 'Esquizofrenia', 'created_at' => Carbon::now()]);
        DB::table('sigges')->insert(['descripcion' => 'Sin caso', 'created_at' => Carbon::now()]);
        DB::table('sigges')->insert(['descripcion' => 'Sospecha EQZ', 'created_at' => Carbon::now()]);
        DB::table('sigges')->insert(['descripcion' => 'Trastorno Bipolar', 'created_at' => Carbon::now()]);

        // Insert prevition
        DB::table('prevision')->insert(['descripcion' => 'FONASA A', 'created_at' => Carbon::now()]);
        DB::table('prevision')->insert(['descripcion' => 'FONASA B', 'created_at' => Carbon::now()]);
        DB::table('prevision')->insert(['descripcion' => 'FONASA C', 'created_at' => Carbon::now()]);
        DB::table('prevision')->insert(['descripcion' => 'FONASA D', 'created_at' => Carbon::now()]);
        DB::table('prevision')->insert(['descripcion' => 'PRAIS', 'created_at' => Carbon::now()]);
        DB::table('prevision')->insert(['descripcion' => 'Sin Previsión', 'created_at' => Carbon::now()]);

        // Insert provenance
        DB::table('procedencia')->insert(['descripcion' => 'APS', 'created_at' => Carbon::now()]);
        DB::table('procedencia')->insert(['descripcion' => 'CAE/CDT/CRS', 'created_at' => Carbon::now()]);
        DB::table('procedencia')->insert(['descripcion' => 'URGENCIA', 'created_at' => Carbon::now()]);
        DB::table('procedencia')->insert(['descripcion' => 'Consulta Espontánea', 'created_at' => Carbon::now()]);
        DB::table('procedencia')->insert(['descripcion' => 'Ateción Terciaria', 'created_at' => Carbon::now()]);
        DB::table('procedencia')->insert(['descripcion' => 'Tribunales', 'created_at' => Carbon::now()]);
        DB::table('procedencia')->insert(['descripcion' => 'Otro', 'created_at' => Carbon::now()]);

        // Insert speciality
        DB::table('especialidad')->insert(['descripcion' => 'Médico', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Médico Psiquiatra', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Psicólogo', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Asistente Social', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Enfermera', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Terapeuta Ocupacional', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Psicopedagogo', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Agente Comunitario', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'TENS', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Profesor Educación Física', 'created_at' => Carbon::now()]);
        DB::table('especialidad')->insert(['descripcion' => 'Administrativo', 'created_at' => Carbon::now()]);

        // Insert types_speciality
        DB::table('especialidad_programa')->insert(['descripcion' => 'Psiquiatría Adulto', 'codigo' => '07-117-2', 'created_at' => Carbon::now()]);
        DB::table('especialidad_programa')->insert(['descripcion' => 'Psiquiatría Infantil', 'codigo' => '07-117-1', 'created_at' => Carbon::now()]);

        // Insert programs
        DB::table('programa')->insert(['descripcion' => 'PAI Infanto', 'especialidad_programa_id' => 2, 'created_at' => Carbon::now()]);
        DB::table('programa')->insert(['descripcion' => 'PAI Adulto', 'especialidad_programa_id' => 1,  'created_at' => Carbon::now()]);
        DB::table('programa')->insert(['descripcion' => 'Adulto', 'especialidad_programa_id' => 1,  'created_at' => Carbon::now()]);
        DB::table('programa')->insert(['descripcion' => 'Infanto Adolescente', 'especialidad_programa_id' => 2,  'created_at' => Carbon::now()]);
    }
}
