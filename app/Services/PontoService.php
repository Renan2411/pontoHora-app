<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\Ponto;

class PontoService
{

    private $pontoModel;
    private $categoriaModel;

    public function __construct(Ponto $pontoModel, Categoria $categoriaModel)
    {
        $this->pontoModel = $pontoModel;
        $this->categoriaModel = $categoriaModel;
    }

    public function create($attributes)
    {
        try {
            if (isset($attributes['fim'])) {
                $attributes['total'] = $this->calculaTotal($attributes['inicio'], $attributes['fim']);

                $attributes['finalizada'] = true;
            }

            $created = $this->pontoModel->create($attributes);

            if ($created) {
                return $data = [
                    'class' => 'success',
                    'message' => 'Ponto Criado com Sucesso!!!'
                ];
            } else {
                return $data = [
                    'class' => 'danger',
                    'message' => 'Houve um erro ao criar o Ponto, tente novamente!!!'
                ];
            }
        } catch (\Throwable $th) {
            return $data = [
                'class' => 'danger',
                'message' => 'Houve um erro ao criar o Ponto, tente novamente!!!'
            ];
        }
    }

    public function edit($attributes)
    {
        try {

            $ponto = $this->pontoModel->find($attributes['ponto_id']);

            if (isset($attributes['fim'])) {
                $attributes['total'] = $this->calculaTotal($attributes['inicio'], $attributes['fim'], $attributes['ponto_id']);
            }

            $edited = $ponto->fill($attributes);
            $edited->save();

            if ($edited) {
                return $data = [
                    'class' => 'success',
                    'message' => 'Ponto editado com Sucesso!!'
                ];
            } else {
                return $data = [
                    'class' => 'danger',
                    'message' => 'Houve um erro ao editar o Ponto, tente novamente!!!'
                ];
            }
            /*
            if ($attributes['fim'] >= $pontoVerificador->fim) {
            } else {
                return $data = [
                    'class' => 'danger',
                    'message' => 'Encontramos um registro no mesmo horário, por favor, edite o existente ou cadastre um novo ponto!!'
                ];
            }
            */
        } catch (\Throwable $th) {
            dd($th);
            return $data = [
                'class' => 'danger',
                'message' => 'Houve um erro ao editar o Ponto, tente novamente!!!'
            ];
        }
    }

    public function calculaTotal($horaInicio, $horaFim, $ponto_id = null)
    {

        //Transformando a string recebida em inteiro para calculo das horas
        $horaInicio = explode(':', $horaInicio);
        $horaInicio[0] = intval($horaInicio[0]);
        $horaInicio[1] = intval($horaInicio[1]);

        $horaFim = explode(':', $horaFim);
        $horaFim[0] = intval($horaFim[0]);
        $horaFim[1] = intval($horaFim[1]);

        //Trasformando para minutos para facilitar o calculo
        $horaInicio = ($horaInicio[0] * 60) + $horaInicio[1];
        $horaFim = ($horaFim[0] * 60) + $horaFim[1];

        //Calculando o total de minutos usados
        $total = $horaFim - $horaInicio;

        //Analisando as pausas
        if ($ponto_id) {
            $total -= $this->calculaPausas($ponto_id);
        }

        //Calsulando as horas passada e tranformando em string
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

        return $total;
    }

    public function calculaPausas($ponto_id)
    {
        $total = 0;
        $pausas = $this->pontoModel->find($ponto_id)->pausas;

        foreach ($pausas as $item) {
            //Fazendo o mesmo processo ali em cima
            $pausa = explode(':', $item->total);

            $pausa[0] = intval($pausa[0]);
            $pausa[1] = intval($pausa[1]);

            $pausa = ($pausa[0] * 60) + $pausa[1];

            //Subtraindo o tempo de pausa do tempo total
            $total += $pausa;
        }

        return $total;
    }
}
