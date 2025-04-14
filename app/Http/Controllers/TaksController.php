<?php

namespace App\Http\Controllers;

use App\Models\Diasemana_tecnico;
use App\Models\Task;
use Illuminate\Http\Request;

class TaksController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'work_week_id' => 'required',
            'task_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'area_tipo' => 'required|in:A,B',
            'priority' => 'required',
            'estimated_hours' => 'required|numeric|min:0.5'
        ]);
    
        // Calcular horas disponibles
        $horasAsignadas = Diasemana_tecnico::where('work_week_id', $request->work_week_id)
            ->where('dia_semana', $request->fecha)
            ->where('area', $request->area)
            ->sum('horas');
    
        $horasUsadas = Task::where('work_week_id', $request->work_week_id)
            ->where('fecha', $request->fecha)
            ->where('area', $request->area)
            ->sum('estimated_hours');
    
        $horasDisponibles = $horasAsignadas - $horasUsadas;

        return $horasAsignadas;
    
        if ($request->estimated_hours > $horasDisponibles) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficientes horas disponibles en esta área. Disponibles: ' . $horasDisponibles . 'h'
            ]);
        }
    
        // Crear la tarea
        // Task::create($request->all());
    
        return response()->json(['success' => true]);
    }
   
}
