<?php

namespace App\Http\Controllers;

use App\Models\Diasemana_tecnico;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaksController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'work_week_id' => 'required',
            'molde_id' => 'required',
            'task_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'area_tipo' => 'required|in:A,B',
            'priority' => 'required',
            'estimated_hours' => 'required|numeric|min:0.5'
        ]);

        // Calcular horas disponibles
        $horasAsignadas = Diasemana_tecnico::where('work_week_id', $request->work_week_id)
            ->where('dia_semana', $request->task_date)
            ->where('area', $request->area_tipo)
            ->sum('horas');

        $horasUsadas = Task::where('work_week_id', $request->work_week_id)
            ->where('fecha', $request->task_date)
            ->where('area', $request->area_tipo)
            ->sum('estimated_hours');

        $horasDisponibles = $horasAsignadas - $horasUsadas;

        // return $horasDisponibles;

        // if ($request->estimated_hours > $horasDisponibles) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'No hay suficientes horas disponibles en esta área. Disponibles: ' . $horasDisponibles . 'h'
        //     ]);
        // }

        // Crear la tarea
        Task::create([
            'work_week_id' =>  $request->work_week_id,
            'molde_id' => $request->molde_id,
            'area' => $request->area_tipo,
            'priority' => $request->priority,
            'fecha' => $request->task_date,
            'hora_inicio' => $request->start_time,
            'hora_fin' => $request->end_time,
            'estimated_hours' => $request->estimated_hours,
            'notes' => $request->notes

        ]);

        return redirect()->back()->with('status', 'Se guardo exitosamente!');
    }
    public function split(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:task,id',
            'split_hours' => 'required|integer|min:1',
            'current_date' => 'required|date',
            'start_timedivi' => 'required'
        ]);

        $task = Task::findOrFail($request->task_id);
        $splitHours = $request->split_hours;

        if ($splitHours >= $task->estimated_hours) {
            return back()->with('error', 'No puedes mover más horas de las que tiene la tarea.');
        }

        $remaining = $task->estimated_hours - $splitHours;
        $hora_divide = Carbon::parse($request->start_timedivi);
        $hora_fin = $hora_divide->addHours($splitHours);

        $horafinoriginal = Carbon::parse($task->hora_inicio);
        $horaorifin= $horafinoriginal->addHours($remaining);
        // Actualizar tarea actual
        $task->update(['estimated_hours' => $remaining, 'hora_fin' => $horaorifin]);

        // Crear tarea para el siguiente día
        $nextDay = Carbon::parse($request->current_date)->addDay();

        Task::create([
            'work_week_id' => $task->work_week_id,
            'molde_id' => $task->molde_id,
            'area' => $task->area,
            'priority' => $task->priority,
            'estimated_hours' => $splitHours,
            'fecha' => $nextDay->toDateString(),
            'hora_inicio' => $request->start_timedivi,
            'hora_fin' => $hora_fin,
            'notes' => $task->notes
        ]);

        return back()->with('success', 'Tarea dividida exitosamente.');
    }
}
