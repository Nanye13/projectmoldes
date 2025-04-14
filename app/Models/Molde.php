<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Molde extends Model
{
    use HasFactory;

    protected $table = "moldes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'tipo_mantenimiento',
        'horas',
        'estatus'
    ];
}
