<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_week extends Model
{
    use HasFactory;
    protected $fillable = [
        'inicio_semana',
        'fin_semana',
        'total_hours',
        'excess_hours_area_a',
        'excess_hours_area_b',
        'horas_movidas'
    ];
    protected $casts = [
        'inicio_semana' => 'datetime',
        'fin_semana' => 'datetime'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public static function creasemana()
    {
        $today = now();
        $iniciosemana = $today->startOfWeek()->format('Y-m-d'); // Lunes
        // $finsemana = $today->endOfWeek()->format('Y-m-d');     // Domingo
        $finsemana = $today->startOfWeek()->addDays(5)->format('Y-m-d'); // Sábado

        return self::firstOrCreate(
            [
                'inicio_semana' => $iniciosemana,
                'fin_semana' => $finsemana,
            ],
            [
                'total_hours' => 0,
                'excess_hours_area_a' => 0,
                'excess_hours_area_b' => 0
            ]
        );
    }
}
