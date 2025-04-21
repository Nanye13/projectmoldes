<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = "task";

    protected $fillable = [
        'work_week_id',
        'molde_id',
        'tecnico_id',
        'area',
        'priority',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estimated_hours',
        'actual_hours',
        'notes',
        'completed'
    ];

    public function workWeek()
    {
        return $this->belongsTo(Work_week::class);
    }
    public function molde()
    {
        return $this->belongsTo(Molde::class, 'id');
    }
}
