<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use Illuminate\Http\Request;

class TecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tecnicos = Tecnico::get();
        return view('plan.tecnicos', compact('tecnicos'));
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
        $area = $request->input('area');

        Tecnico::create([
            'nombre' => $nombre,
            'area' => $area,
            'estatus' => 1
        ]);
        return redirect()->back()->with('status', 'El Técnico se agrego correctamente!');
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
        $area = $request->input('aream');

        $tecnico = Tecnico::find($id);
        $tecnico->nombre = $nombre;
        $tecnico->area = $area;
        $tecnico->save();

        return redirect()->back()->with('status', 'El Técnico se agrego correctamente!');
    }
    public function bajatecnico($id)
    {
        $tecnico = Tecnico::find($id);
        $tecnico->estatus = 0;
        $tecnico->save();
        return redirect()->back()->with('status', 'El Técnico se dio de baja correctamente!');

    }

    public function altatecnico($id){
        $tecnico = Tecnico::find($id);
        $tecnico->estatus = 1;
        $tecnico->save();
        return redirect()->back()->with('status', 'El Técnico se dio de alta correctamente!');

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
