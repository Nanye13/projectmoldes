<?php

namespace App\Http\Controllers;

use App\Models\Molde;
use Illuminate\Http\Request;

class MoldeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $moldes = Molde::get();
        return view('plan.moldes', compact('moldes'));
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
        $nombre = $request->input('nombre');
        $tipo = $request->input('tipo');
        $horas = $request->input('horas');

        Molde::create([
            'nombre' => $nombre,
            'tipo_mantenimiento' => $tipo,
            'horas' => $horas,
            'estatus' => 1
        ]);
        return redirect()->back()->with('status','El molde se agrego correctamente!');
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
        $nombre = $request->input('nombrem');
        $tipo = $request->input('tipom');
        $horas = $request->input('horasm');
        $molde = Molde::find($id);
        $molde->nombre = $nombre;
        $molde->tipo_mantenimiento = $tipo;
        $molde->horas = $horas;
        $molde->save();
        return redirect()->back()->with('status','El molde se edito correctamente!');
    }
    public function darbaja($id)
    {
        $molde = Molde::find($id);
        $molde->estatus = 0;
        $molde->save();
        return redirect()->back()->with('status','El molde se dio de baja correctamente!');

    }
    public function altamol($id){
        $molde = Molde::find($id);
        $molde->estatus = 1;
        $molde->save();
        return redirect()->back()->with('status','El molde se dio de alta correctamente!');

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
