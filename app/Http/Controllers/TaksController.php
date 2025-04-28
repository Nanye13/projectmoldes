<?php

namespace App\Http\Controllers;

use App\Models\Diasemana_tecnico;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaksController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'work_week_id' => 'required',
    //         'molde_id' => 'required',
    //         'task_date' => 'required|date',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'area_tipo' => 'required|in:A,B',
    //         'priority' => 'required',
    //         'estimated_hours' => 'required|numeric|min:0.5'
    //     ]);

    //     // Calcular horas disponibles
    //     $horasAsignadas = Diasemana_tecnico::where('work_week_id', $request->work_week_id)
    //         ->where('dia_semana', $request->task_date)
    //         ->where('area', $request->area_tipo)
    //         ->sum('horas');

    //     $horasUsadas = Task::where('work_week_id', $request->work_week_id)
    //         ->where('fecha', $request->task_date)
    //         ->where('area', $request->area_tipo)
    //         ->sum('estimated_hours');

    //     $horasDisponibles = $horasAsignadas - $horasUsadas;

    //     // return $horasDisponibles;


    //     // Crear la tarea
    //     Task::create([
    //         'work_week_id' =>  $request->work_week_id,
    //         'molde_id' => $request->molde_id,
    //         'area' => $request->area_tipo,
    //         'priority' => $request->priority,
    //         'fecha' => $request->task_date,
    //         'hora_inicio' => $request->start_time,
    //         'hora_fin' => $request->end_time,
    //         'estimated_hours' => $request->estimated_hours,
    //         'notes' => $request->notes

    //     ]);

    //     return redirect()->back()->with('status', 'Se guardo exitosamente!');
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'work_week_id' => 'required',
    //         'molde_id' => 'required',
    //         'task_date' => 'required|date',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'area_tipo' => 'required|in:A,B',
    //         'priority' => 'required',
    //         'estimated_hours' => 'required|numeric|min:0.5'
    //     ]);

    //     $estimatedHoursRemaining = $request->estimated_hours;
    //     $taskDate = Carbon::parse($request->task_date);
    //     $startTime = Carbon::parse($request->task_date . ' ' . $request->start_time);

    //     // CORREGIR HORA DE INICIO si es antes de las 7:00 am
    //     $startLimit = Carbon::parse($taskDate->toDateString() . ' 07:00:00');
    //     if ($startTime->lt($startLimit)) {
    //         $startTime = $startLimit->copy();
    //     }

    //     $partNumber = 1;
    //     $notesBase = $request->notes ?? '';
    //     $tasksCreated = 0;

    //     // Control de intentos para evitar bucles infinitos
    //     $maxAttempts = 30; // Máximo de días a intentar
    //     $attempts = 0;

    //     while ($estimatedHoursRemaining > 0 && $attempts < $maxAttempts) {

    //         // Brincar domingos
    //         while ($taskDate->isSunday()) {
    //             $taskDate->addDay();
    //         }

    //         $dayOfWeek = $taskDate->dayOfWeek;

    //         if ($dayOfWeek >= 1 && $dayOfWeek <= 5) { // Lunes a viernes
    //             $endLimit = Carbon::parse($taskDate->toDateString() . ' 23:00:00');
    //         } elseif ($dayOfWeek == 6) { // Sábado
    //             $endLimit = Carbon::parse($taskDate->toDateString() . ' 20:00:00');
    //         }

    //         // Ajustar si startTime ya pasó el horario permitido
    //         if ($startTime->gt($endLimit)) {
    //             $taskDate->addDay();
    //             $startTime = Carbon::parse($taskDate->toDateString() . ' 07:00:00');
    //             $attempts++;
    //             continue;
    //         }

    //         // Calcular espacio disponible real considerando tareas ya guardadas
    //         $horasAsignadas = Diasemana_tecnico::where('work_week_id', $request->work_week_id)
    //             ->where('dia_semana', $taskDate->toDateString())
    //             ->where('area', $request->area_tipo)
    //             ->sum('horas');

    //         $horasUsadas = Task::where('work_week_id', $request->work_week_id)
    //             ->where('fecha', $taskDate->toDateString())
    //             ->where('area', $request->area_tipo)
    //             ->sum('estimated_hours');

    //         $horasDisponiblesBase = $horasAsignadas - $horasUsadas;

    //         $availableHoursToday = min(
    //             $horasDisponiblesBase,
    //             $startTime->diffInMinutes($endLimit) / 60
    //         );

    //         if ($availableHoursToday <= 0) {
    //             // No hay horas disponibles hoy, pasar al siguiente día
    //             $taskDate->addDay();
    //             $startTime = Carbon::parse($taskDate->toDateString() . ' 07:00:00');
    //             $attempts++;
    //             continue;
    //         }

    //         // Definir horas que se pueden asignar hoy
    //         $hoursToday = min($estimatedHoursRemaining, $availableHoursToday);

    //         $endTime = $startTime->copy()->addMinutes($hoursToday * 60);

    //         // Crear tarea
    //         Task::create([
    //             'work_week_id' => $request->work_week_id,
    //             'molde_id' => $request->molde_id,
    //             'area' => $request->area_tipo,
    //             'priority' => $request->priority,
    //             'fecha' => $taskDate->toDateString(),
    //             'hora_inicio' => $startTime->format('H:i:s'),
    //             'hora_fin' => $endTime->format('H:i:s'),
    //             'estimated_hours' => $hoursToday,
    //             'notes' => $notesBase . ' (Parte ' . $partNumber . ')'
    //         ]);

    //         $tasksCreated++;
    //         $partNumber++;

    //         $estimatedHoursRemaining -= $hoursToday;

    //         // Preparar para siguiente día
    //         $taskDate->addDay();
    //         $startTime = Carbon::parse($taskDate->toDateString() . ' 07:00:00');

    //         $attempts++;
    //     }

    //     // Validar si hubo un corte de intentos
    //     if ($attempts >= $maxAttempts && $estimatedHoursRemaining > 0) {
    //         return redirect()->back()->with('error', "No se pudo asignar toda la tarea. Faltan {$estimatedHoursRemaining} horas por programar.");
    //     }

    //     return redirect()->back()->with('status', "¡Se guardaron {$tasksCreated} tarea(s) exitosamente!");
    // }


    public function store(Request $request)
    {
        $request->validate([
            'work_week_id' => 'required',
            'molde_id' => 'required',
            'task_date' => 'required|date',
            'start_time' => 'required',
            'area_tipo' => 'required|in:A,B',
            'priority' => 'required',
            'estimated_hours' => 'required|numeric|min:0.5'
        ]);

        $taskDate = Carbon::parse($request->task_date);
        $startTime = Carbon::parse($taskDate->toDateString() . ' ' . $request->start_time);

        $startLimit = Carbon::parse($taskDate->toDateString() . ' 07:00:00');
        if ($startTime->lt($startLimit)) {
            return redirect()->back()->withErrors(['error' => 'La tarea no puede iniciar antes de las 7:00 am.'])->withInput();
        }

        // Horario límite de ese día
        $endLimit = Carbon::parse($taskDate->toDateString() . ' 23:00:00');
        $requestedHours = $request->estimated_hours;
        $endTime = $startTime->copy()->addMinutes($requestedHours * 60);

        DB::beginTransaction();
        try {
            // Verificar horas disponibles en el día
            $horasAsignadasHoy = Diasemana_tecnico::where('work_week_id', $request->work_week_id)
                ->where('dia_semana', $taskDate->toDateString())
                ->where('area', $request->area_tipo)
                ->sum('horas');

            $horasUsadasHoy = Task::where('work_week_id', $request->work_week_id)
                ->where('fecha', $taskDate->toDateString())
                ->where('area', $request->area_tipo)
                ->sum('estimated_hours');

            $horasDisponiblesHoy = $horasAsignadasHoy - $horasUsadasHoy;

            if ($horasDisponiblesHoy <= 0) {
                return redirect()->back()->withErrors(['error' => 'El día ya está lleno. No se pueden agendar más tareas.'])->withInput();
            }

            // Si termina después de las 11 pm
            if ($endTime->greaterThan($endLimit)) {
                // Calcular minutos disponibles hasta las 11:00 PM
                $minutosDisponiblesHoy = $startTime->diffInMinutes($endLimit);
                $horasHoy = $minutosDisponiblesHoy / 60;

                // Asegurarse que haya espacio
                if ($horasHoy > $horasDisponiblesHoy) {
                    $horasHoy = $horasDisponiblesHoy;
                }

                if ($horasHoy > 0) {
                    // Guardar primera parte de la tarea (hoy)
                    Task::create([
                        'work_week_id' => $request->work_week_id,
                        'molde_id' => $request->molde_id,
                        'area' => $request->area_tipo,
                        'priority' => $request->priority,
                        'fecha' => $taskDate->toDateString(),
                        'hora_inicio' => $startTime->format('H:i:s'),
                        'hora_fin' => $endLimit->format('H:i:s'),
                        'estimated_hours' => $horasHoy,
                        'notes' => $request->notes ?? null
                    ]);
                }

                // Calcular horas restantes
                $horasRestantes = $requestedHours - $horasHoy;

                if ($horasRestantes > 0) {
                    // Preparar siguiente día
                    $nextDay = $taskDate->copy()->addDay();
                    $nextStart = Carbon::parse($nextDay->toDateString() . ' 07:00:00');
                    $nextEnd = $nextStart->copy()->addMinutes($horasRestantes * 60);

                    // Verificar horas disponibles el siguiente día
                    $horasAsignadasSiguiente = Diasemana_tecnico::where('work_week_id', $request->work_week_id)
                        ->where('dia_semana', $nextDay->toDateString())
                        ->where('area', $request->area_tipo)
                        ->sum('horas');

                    $horasUsadasSiguiente = Task::where('work_week_id', $request->work_week_id)
                        ->where('fecha', $nextDay->toDateString())
                        ->where('area', $request->area_tipo)
                        ->sum('estimated_hours');

                    $horasDisponiblesSiguiente = $horasAsignadasSiguiente - $horasUsadasSiguiente;

                    if ($horasDisponiblesSiguiente < $horasRestantes) {
                        DB::rollback();
                        return redirect()->back()->withErrors(['error' => 'No hay suficientes horas disponibles el siguiente día para completar la tarea.'])->withInput();
                    }

                    // Guardar segunda parte de la tarea (siguiente día)
                    Task::create([
                        'work_week_id' => $request->work_week_id,
                        'molde_id' => $request->molde_id,
                        'area' => $request->area_tipo,
                        'priority' => $request->priority,
                        'fecha' => $nextDay->toDateString(),
                        'hora_inicio' => $nextStart->format('H:i:s'),
                        'hora_fin' => $nextEnd->format('H:i:s'),
                        'estimated_hours' => $horasRestantes,
                        'notes' => $request->notes ?? null
                    ]);
                }
            } else {
                // No excede las 11:00 PM, guardar normal
                if ($requestedHours > $horasDisponiblesHoy) {
                    return redirect()->back()->withErrors(['error' => 'No hay suficientes horas disponibles para esta tarea.'])->withInput();
                }

                Task::create([
                    'work_week_id' => $request->work_week_id,
                    'molde_id' => $request->molde_id,
                    'area' => $request->area_tipo,
                    'priority' => $request->priority,
                    'fecha' => $taskDate->toDateString(),
                    'hora_inicio' => $startTime->format('H:i:s'),
                    'hora_fin' => $endTime->format('H:i:s'),
                    'estimated_hours' => $requestedHours,
                    'notes' => $request->notes ?? null
                ]);
            }

            DB::commit();
            return redirect()->back()->with('status', '¡Tarea guardada exitosamente, ajustada si era necesario!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al guardar la tarea.'])->withInput();
        }
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
        $horaorifin = $horafinoriginal->addHours($remaining);
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
