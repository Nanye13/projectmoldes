<?php

namespace App\Http\Controllers;

use App\Models\Diasemana_tecnico;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiaSemanaTecController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $semana_id = $request->input('id_semana');
        $dia_semana = $request->input('dia');
        $tecnico_id = $request->input('tecnico_id');
        $horas = $request->input('horas');

        $validator = Validator::make($request->all(), [
            'id_semana' => ['required'],
            'dia' => ['required'],
            'tecnico_id' => ['required'],
            'horas'  => ['required'],
        ], [
            'tecnico_id.required' => 'El campo de técnico es requerido',
            'horas.required' => 'El campo de horas es requerido'
        ]);

        // Si la validación falla, retorna con los errores
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tecnico = Tecnico::find($tecnico_id);
        Diasemana_tecnico::create([
            'work_week_id' => $semana_id,
            'dia_semana' => $dia_semana,
            'tecnico_id' => $tecnico_id,
            'horas' => $horas,
            'estatus' => 1,
            'area' => $tecnico->area
        ]);

        return redirect()->back()->with('status', 'Se ha registrado correctamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
