<?php

namespace App\Http\Controllers;

use App\Models\Pausa;
use App\Services\PausaService;
use Illuminate\Http\Request;

class PausaController extends Controller
{

    private $pausaService;

    public function __construct(PausaService $pausaService)
    {
        $this->pausaService = $pausaService;
    }

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
        $data = $this->pausaService->create($request->all());

        return redirect()->back()->with($data['class'], $data['message']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pausa  $pausa
     * @return \Illuminate\Http\Response
     */
    public function show(Pausa $pausa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pausa  $pausa
     * @return \Illuminate\Http\Response
     */
    public function edit(Pausa $pausa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pausa  $pausa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->pausaService->edit($request->all());
        
        return redirect()->back()->with($data['class'], $data['message']);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pausa  $pausa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pausa $pausa)
    {
        //
    }
}
