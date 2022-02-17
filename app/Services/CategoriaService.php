<?php

namespace App\Services;

use App\Models\Ponto;
use Illuminate\Support\Facades\Auth;

class CategoriaService
{

    private $pontoModel;
    private $pontoService;

    public function __construct(Ponto $pontoModel, PontoService $pontoService)
    {
        $this->pontoModel = $pontoModel;
        $this->pontoService = $pontoService;
    }

    public function index()
    {
        $categorias = Auth::user()->categorias;

        $ultimasEntradas = $totalPausas = [];

        foreach ($categorias as $item) {

            $entrada = $this->pontoModel->where('categoria_id', $item->id)->orderby('data', 'desc')->first();

            if ($entrada) {
                $total = $this->pontoService->calculaPausas($entrada->id);

                //Calculando as horas passadas e transformando em String
                $horaTotal = strval(intdiv($total, 60));

                //Calculando os minutos passados e transformando em string
                $minTotal = strval($total % 60);

                //Ajustando o 0 na frente para fins de padronização
                if ($horaTotal < 10) {
                    $horaTotal = '0' . $horaTotal;
                }

                //Ajustando o 0 na frente para fins de padronização
                if ($minTotal < 10) {
                    $minTotal = '0' . $minTotal;
                }

                //Ajustando o formato a ser armazenado
                $total = $horaTotal . ":" . $minTotal;

                array_push($ultimasEntradas, $entrada->toArray());

                array_push($totalPausas, $total);
            } 
        }

        return $dados = [
            'categorias' => $categorias,
            'ultimasEntradas' => $ultimasEntradas,
            'totalPausas' => $totalPausas
        ];
    }
}
