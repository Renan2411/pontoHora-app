<?php

namespace App\Http\Controllers;

use App\Models\Ponto;
use App\Services\PontoService;
use Illuminate\Http\Request;

class PontoController extends Controller
{

    private $pontoService;
    private $pontoModel;

    public function __construct(PontoService $pontoService, Ponto $pontoModel)
    {
        $this->pontoService = $pontoService;
        $this->pontoModel = $pontoModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoria_id)
    {
        $pontos = $this->pontoModel->where('categoria_id', $categoria_id)->orderby('data', 'desc')->get();

        return view('painel.pontos.index',[
            'categoria_id' => $categoria_id,
            'pontos' => $pontos
        ]);
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
        $data = $this->pontoService->create($request->all());

        return redirect()->back()->with($data['class'], $data['message']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function show(Ponto $ponto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function edit(Ponto $ponto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ponto $ponto)
    {
        $data = $this->pontoService->edit($request->all());

        return redirect()->back()->with($data['class'], $data['message']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ponto $ponto)
    {
        //
    }
}
