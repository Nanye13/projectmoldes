<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diasemana_tecnico extends Model
{
    use HasFactory;
    protected $table = "diasemana_tecnico";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_week_id',
        'dia_semana',
        'tecnico_id',
        'horas',
        'estatus'
    ];

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'id_tecnico');
    }
}
