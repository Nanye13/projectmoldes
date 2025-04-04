<?php

namespace App\Http\Controllers;

use App\Models\Diasemana_tecnico;
use App\Models\Tecnico;
use App\Models\Work_week;
use Illuminate\Http\Request;

class PlanSemanalController extends Controller
{
    //
    public function index()
    {
        $semana_actual = Work_week::creasemana();
        $weekDays = $this->prepareWeekDays($semana_actual);
        $tecnicos = Tecnico::where('estatus', 1)->get();
        // return $weekDays;

        return view('plan.plansemanal', compact('semana_actual', 'weekDays', 'tecnicos'));
    }

    protected function prepareWeekDays(Work_week $week)
    {
        $days = [];
        $currentDate = $week->inicio_semana->copy();
        \Carbon\Carbon::setLocale('es');
        setlocale(LC_TIME, 'spanish');

        // Obtener todas las configuraciones de horas y técnicos agrupadas por día
        $dayAssignments = Diasemana_tecnico::with('tecnico') // Carga la relación con técnico
            ->where('work_week_id', $week->id)
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->dia_semana)->format('Y-m-d');
            });

        while ($currentDate <= $week->fin_semana) {
            $dayName = $currentDate->isoFormat('dddd');
            $dateString = $currentDate->toDateString();

            // Obtener las asignaciones para este día
            $assignments = $dayAssignments[$dateString] ?? collect();

            // Calcular límites de horas por área
            $totalLimitA = $assignments->where('area', 'A')->sum('horas');
            $totalLimitB = $assignments->where('area', 'B')->sum('horas');

            // Obtener tareas del día
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
                    return [
                        'id' => $assignment->id,
                        'tecnico_id' => $assignment->tecnico_id,
                        'nombre' => $assignment->tecnico->nombre ?? 'N/A',
                        'area' => $assignment->area,
                        'horas' => $assignment->horas
                    ];
                })->values()->toArray()
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
