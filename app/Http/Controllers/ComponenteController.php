<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateComponenteRequest;
use App\Models\Componente;

class ComponenteController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreComponenteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($componentName, $contents, $data)
    {
        //dd($componentName, $contents, $data);

        $document_id = $data->document_id;
        Componente::create([
            'name'=>$componentName,
            'conteudo'=>$contents,
            'document_id'=>$document_id
        ]);
    }

    public function update(Request $request){
        //dd($request);
        $id = $request->id;
        $componente = Componente::where('document_id', '=', $id)->first();
        $componente->update($request->all('conteudo'));
    }

    public function destroy($id){
        $componente = Componente::where('document_id', '=', $id)->first();
        $componente->delete('DELETE FROM componentes WHERE document_id = ?', [$id]);
    }

}
