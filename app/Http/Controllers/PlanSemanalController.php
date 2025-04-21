<?php

namespace App\Http\Controllers;

use App\Models\Diasemana_tecnico;
use App\Models\Molde;
use App\Models\Tecnico;
use App\Models\Work_week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanSemanalController extends Controller
{
    //
    public function index()
    {
        $semana_actual = Work_week::creasemana();
        $weekDays = $this->prepareWeekDays($semana_actual);
        $tecnicos = Tecnico::where('estatus', 1)->get();
        $moldes = Molde::where('estatus', 1)->get();
        // return $weekDays;

        return view('plan.plansemanal', compact('semana_actual', 'weekDays', 'tecnicos', 'moldes'));
    }

    protected function prepareWeekDays(Work_week $week)
    {
        $days = [];
        $currentDate = $week->inicio_semana->copy();
        // Obtener todas las asignaciones de técnicos
        $tecnicosPorDia = Diasemana_tecnico::with('tecnico')
            ->where('work_week_id', $week->id)
            ->get()
            ->groupBy('dia_semana');

        // Obtener todas las tareas agrupadas por fecha
        // $tareasPorDia = $week->tasks()
        //     ->with('molde') // Cargamos el molde relacionado
        //     ->orderBy('priority')
        //     ->get()
        //     ->groupBy(function ($task) {
        //         return Carbon::parse($task->fecha)->format('Y-m-d');
        //     });

        $tareasPorDia = DB::table('task')
            ->join('moldes', 'task.molde_id', '=', 'moldes.id')
            ->where('task.work_week_id', $week->id) // filtra por semana
            ->select('task.*', 'moldes.nombre as nombre_molde')
            ->orderBy('task.priority')
            ->get()
            ->groupBy(function ($task) {
                return Carbon::parse($task->fecha)->format('Y-m-d');
            });


        while ($currentDate <= $week->fin_semana) {
            // Saltar si es domingo
            if ($currentDate->dayOfWeek === Carbon::SUNDAY) {
                $currentDate->addDay();
                continue;
            }

            $dateString = $currentDate->format('Y-m-d');
            $dayName = $currentDate->isoFormat('dddd');

            // Horas asignadas por área
            $asignaciones = $tecnicosPorDia->get($dateString, collect());
            $horasAsignadasA = $asignaciones->where('area', 'A')->sum('horas');
            $horasAsignadasB = $asignaciones->where('area', 'B')->sum('horas');

            // Tareas del día ordenadas por prioridad
            $tareasDia = $tareasPorDia->get($dateString, collect());
            $horasUsadasA = $tareasDia->where('area', 'A')->sum('estimated_hours');
            $horasUsadasB = $tareasDia->where('area', 'B')->sum('estimated_hours');



            $days[] = [
                'date' => $dateString,
                'name' => $dayName,
                'tasks' => $tareasDia,
                'horas_asignadas' => [
                    'A' => $horasAsignadasA,
                    'B' => $horasAsignadasB
                ],
                'horas_utilizadas' => [
                    'A' => $horasUsadasA,
                    'B' => $horasUsadasB
                ],
                'horas_disponibles' => [
                    'A' => $horasAsignadasA - $horasUsadasA,
                    'B' => $horasAsignadasB - $horasUsadasB
                ],
                'tecnicos' => $asignaciones->map(function ($asign) {
                    return [
                        'id' => $asign->id,
                        'nombre' => $asign->tecnico->nombre ?? 'N/A',
                        'area' => $asign->area,
                        'horas' => $asign->horas
                    ];
                })->values()
            ];

            $currentDate->addDay();
        }
        return $days;
    }
    //     while ($currentDate <= $week->fin_semana) {
    //         $dateString = $currentDate->format('Y-m-d');
    //         $dayName = $currentDate->isoFormat('dddd');

    //         // Horas asignadas por área
    //         $asignaciones = $tecnicosPorDia->get($dateString, collect());
    //         $horasAsignadasA = $asignaciones->where('area', 'A')->sum('horas');

    //         // return $asignaciones;
    //         $horasAsignadasB = $asignaciones->where('area', 'B')->sum('horas');

    //         // Tareas del día ordenadas por prioridad
    //         $tareasDia = $tareasPorDia->get($dateString, collect());
    //         $horasUsadasA = $tareasDia->where('area', 'A')->sum('estimated_hours');
    //         $horasUsadasB = $tareasDia->where('area', 'B')->sum('estimated_hours');

    //         $days[] = [
    //             'date' => $dateString,
    //             'name' => $dayName,
    //             'tasks' => $tareasDia,
    //             'horas_asignadas' => [
    //                 'A' => $horasAsignadasA,
    //                 'B' => $horasAsignadasB
    //             ],
    //             'horas_utilizadas' => [
    //                 'A' => $horasUsadasA,
    //                 'B' => $horasUsadasB
    //             ],
    //             'horas_disponibles' => [
    //                 'A' => $horasAsignadasA - $horasUsadasA,
    //                 'B' => $horasAsignadasB - $horasUsadasB
    //             ],
    //             'tecnicos' => $asignaciones->map(function($asign) {
    //                 return [
    //                     'id' => $asign->id,
    //                     'nombre' => $asign->tecnico->nombre ?? 'N/A',
    //                     'area' => $asign->area,
    //                     'horas' => $asign->horas
    //                 ];
    //             })->values()
    //         ];

    //         $currentDate->addDay();
    //     }

    //     return $days;
    // }













    protected function prepareWeekDaysSEGUNDAFUNCION(Work_week $week)
    {
        // $days = [];
        // $currentDate = $week->inicio_semana->copy();
        // \Carbon\Carbon::setLocale('es');
        // setlocale(LC_TIME, 'spanish');

        // // Obtener todas las configuraciones de horas y técnicos agrupadas por día
        // $dayAssignments = Diasemana_tecnico::with('tecnico') // Carga la relación con técnico
        //     ->where('work_week_id', $week->id)
        //     ->get()
        //     ->groupBy(function ($item) {
        //         return \Carbon\Carbon::parse($item->dia_semana)->format('Y-m-d');
        //     });

        // while ($currentDate <= $week->fin_semana) {
        //     $dayName = $currentDate->isoFormat('dddd');
        //     $dateString = $currentDate->toDateString();

        //     // Obtener las asignaciones para este día
        //     $assignments = $dayAssignments[$dateString] ?? collect();


        //     // Calcular límites de horas por área
        //     $totalLimitA = $assignments->where('area', 'A')->sum('horas');
        //     $totalLimitB = $assignments->where('area', 'B')->sum('horas');

        //     // Obtener tareas del día
        //     $tasks = $week->tasks()->whereDate('fecha', $currentDate)->orderBy('priority')->get();
        //     $totalHoursA = $tasks->where('area', 'A')->sum('estimated_hours');
        //     $totalHoursB = $tasks->where('area', 'B')->sum('estimated_hours');


        //     $days[] = [
        //         'date' => $dateString,
        //         'name' => $dayName,
        //         'tasks' => $tasks,
        //         'limit_A' => $totalLimitA,
        //         'limit_B' => $totalLimitB,
        //         'hours_A' => $totalHoursA,
        //         'hours_B' => $totalHoursB,
        //         'exceeded_A' => $totalHoursA > $totalLimitA,
        //         'exceeded_B' => $totalHoursB > $totalLimitB,
        //         'tecnicos' => $assignments->map(function ($assignment) {
        //             return [
        //                 'id' => $assignment->id,
        //                 'tecnico_id' => $assignment->tecnico_id,
        //                 'nombre' => $assignment->tecnico->nombre ?? 'N/A',
        //                 'area' => $assignment->area,
        //                 'horas' => $assignment->horas
        //             ];
        //         })->values()->toArray()
        //     ];

        //     $currentDate->addDay();
        // }

        // return $days;
        // funcion nueva 
        $days = [];
        $currentDate = $week->inicio_semana->copy();
        \Carbon\Carbon::setLocale('es');
        setlocale(LC_TIME, 'spanish');

        // Obtener todas las asignaciones con sus técnicos
        $dayAssignments = Diasemana_tecnico::with(['tecnico' => function ($query) {
            $query->select('id', 'nombre'); // Solo cargar los campos necesarios
        }])
            ->where('work_week_id', $week->id)
            ->get()
            ->groupBy('dia_semana'); // Agrupar directamente por dia_semana (sin parsear)

        while ($currentDate <= $week->fin_semana) {
            $dayName = $currentDate->isoFormat('dddd');
            $dateString = $currentDate->toDateString();

            // Obtener asignaciones para este día (coincidiendo con el formato de dia_semana)
            $assignments = $dayAssignments->get($dateString, collect());

            // Verificar datos (para debugging)
            if ($assignments->isNotEmpty() && !$assignments->first()->relationLoaded('tecnico')) {
                Log::error('Relación técnico no cargada para asignación: ' . $assignments->first()->id);
            }

            // Calcular límites y obtener tareas...
            $totalLimitA = $assignments->where('area', 'A')->sum('horas');
            $totalLimitB = $assignments->where('area', 'B')->sum('horas');

            $tasks = $week->tasks()->whereDate('fecha', $currentDate)->orderBy('priority')->get();
            $totalHoursA = $tasks->where('area', 'A')->sum('estimated_hours');
            $totalHoursB = $tasks->where('area', 'B')->sum('estimated_hours');

            $days[] = [
                'date' => $dateString,
                'name' => $dayName,
                'tasks' => $tasks,
                'limit_A' => $totalLimitA,
                'limit_B' => $totalLimitB,
                'hours_A' => $totalHoursA,
                'hours_B' => $totalHoursB,
                'exceeded_A' => $totalHoursA > $totalLimitA,
                'exceeded_B' => $totalHoursB > $totalLimitB,
                'tecnicos' => $assignments->map(function ($assignment) {
                    if (!$assignment->tecnico) {
                        Log::warning("Técnico no encontrado para asignación ID: {$assignment->id}");
                        return null;
                    }
                    return [
                        'id' => $assignment->id,
                        'tecnico_id' => $assignment->tecnico_id,
                        'nombre' => $assignment->tecnico->nombre,
                        'area' => $assignment->area,
                        'horas' => $assignment->horas
                    ];
                })->filter()->values()->toArray() // Filtrar nulos y reindexar
            ];

            $currentDate->addDay();
        }

        return $days;
    }

    protected function prepareWeekDaysPRIMERAFUNCION(Work_week $week)
    {
        $days = [];
        $currentDate = $week->inicio_semana->copy(); // Usa copy() en lugar de clone
        // Establece el idioma en español
        \Carbon\Carbon::setLocale('es');
        setlocale(LC_TIME, 'spanish');
        // Obtener todas las configuraciones de horas desde diasemana_tecnico
        $limitsByDay = Diasemana_tecnico::where('work_week_id', $week->id)
            ->get()
            ->groupBy('dia_semana'); // Agrupar por fecha del día

        while ($currentDate <= $week->fin_semana) {
            // $dayName = strtolower($currentDate->format('l'));
            // $dayName = strtolower($currentDate->isoFormat('dddd')); // Ej: "lunes"
            $dayName = $currentDate->isoFormat('dddd'); // Devuelve "lunes", "martes", etc. en UTF-8 correcto
            $dateString = $currentDate->toDateString();

            // Obtener los límites de horas de áreas A y B
            $dayLimits = $limitsByDay[$dateString] ?? collect();
            $totalLimitA = $dayLimits->where('area', 'A')->sum('horas');
            $totalLimitB = $dayLimits->where('area', 'B')->sum('horas');

            // Obtener tareas del día y calcular el total de horas
            $tasks = $week->tasks()->whereDate('fecha', $currentDate)->orderBy('priority')->get();
            $totalHoursA = $tasks->where('area', 'A')->sum('estimated_hours');
            $totalHoursB = $tasks->where('area', 'B')->sum('estimated_hours');

            // Detectar si se excede el límite en alguna área
            $exceededA = $totalHoursA > $totalLimitA;
            $exceededB = $totalHoursB > $totalLimitB;

            $days[] = [
                'date' => $dateString,
                // 'name' => trans("days.$dayName"),s
                'name' => $dayName,
                'tasks' => $tasks,
                'limit_A' => $totalLimitA,
                'limit_B' => $totalLimitB,
                'hours_A' => $totalHoursA,
                'hours_B' => $totalHoursB,
                'exceeded_A' => $exceededA,
                'exceeded_B' => $exceededB
            ];

            $currentDate->addDay(); // Avanza al siguiente día
        }

        return $days;
    }
}
