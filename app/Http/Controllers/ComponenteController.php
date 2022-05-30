<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateComponenteRequest;
use App\Models\Componente;

class ComponenteController extends Controller
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
     * @param  \App\Http\Requests\StoreComponenteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data)
    {
        $value = explode(">", $request->value)[0];
        $nome = $value.">";
        $conteudo = $request->value;
        $document_id = $data->document_id;
        Componente::create([
            'name'=>$nome,
            'conteudo'=>$conteudo,
            'document_id'=>$document_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Componente  $componente
     * @return \Illuminate\Http\Response
     */
    public function show(Componente $componente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Componente  $componente
     * @return \Illuminate\Http\Response
     */
    public function edit(Componente $componente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComponenteRequest  $request
     * @param  \App\Models\Componente  $componente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComponenteRequest $request, Componente $componente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Componente  $componente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Componente $componente)
    {
        //
    }
}
