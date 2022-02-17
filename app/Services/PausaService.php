<?php

namespace App\Services;

use App\Models\Pausa;
use App\Models\Ponto;

class PausaService
{

    private $pontoService;
    private $pontoModel;
    private $pausaModel;

    public function __construct(PontoService $pontoService, Pausa $pausaModel, Ponto $pontoModel)
    {
        $this->pontoService = $pontoService;
        $this->pausaModel = $pausaModel;
        $this->pontoModel = $pontoModel;
    }

    public function verificaPausa($attributes)
    {
        try {

            //Se tiver o inicio igual a de uma pausa cadastrada
            $pausa = $this->pausaModel->where('ponto_id', $attributes['ponto_id'])->where('inicio', $attributes['inicio'])->get();

            if (count($pausa)) {
                return true;
            }

            //Se tiver um fim igual a de uma pausa cadastrada
            $pausa = $this->pausaModel->where('ponto_id', $attributes['ponto_id'])->where('fim', $attributes['fim'])->get();

            if (count($pausa)) {
                return true;
            }

            //Se tiver entre os horários de uma pausa cadastrada
            $pausa = $this->pausaModel->where('ponto_id', $attributes['ponto_id'])->where('inicio', '<=', $attributes['inicio'])->where('fim', '>=', $attributes['fim'])->get();

            if (count($pausa)) {
                return true;
            }

            //Se tiver um inicio diferente, mas o final entre os tempos de uma pausa
            $pausa = $this->pausaModel->where('ponto_id', $attributes['ponto_id'])->where('inicio', '<=', $attributes['fim'])->where('fim', '>', $attributes['fim'])->get();

            if (count($pausa)) {
                return true;
            }

            //Se tiver um inicio entre os tempos de uma pausa
            $pausa = $this->pausaModel->where('ponto_id', $attributes['ponto_id'])->where('inicio', '<=', $attributes['inicio'])->where('fim', '>=', $attributes['inicio'])->get();

            if (count($pausa)) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return $data = [
                'class' => 'danger'
            ];
        }
    }

    public function create($attributes)
    {
        try {
            $pausa = $this->verificaPausa($attributes);

            if ($pausa || isset($pausa['class'])) {
                return $data = [
                    'class' => 'danger',
                    'message' => 'Já existe uma pausa cadastrada neste mesmo horário, edite a existente ou cadastre uma nova em um horário diferente!!!'
                ];
            }

            $attributes['total'] = $this->pontoService->calculaTotal($attributes['inicio'], $attributes['fim']);
            // dd($attributes);
            $created = $this->pausaModel->create($attributes);

            if ($created) {

                $ponto = [
                    'inicio' => $created->ponto->inicio,
                    'fim' => $created->ponto->fim,
                    'ponto_id' => $created->ponto->id
                ];

                $ponto = $this->pontoService->edit($ponto);

                if ($ponto['class'] == 'success') {
                    return $data = [
                        'class' => 'success',
                        'message' => 'Pausa cadastrada com sucesso'
                    ];
                } else {
                    return $data = [
                        'class' => 'danger',
                        'message' => 'Houve um erro interno, tente novamente!!!'
                    ];
                }
            } else {
                return $data = [
                    'class' => 'danger',
                    'message' => 'Houve um erro ao cadastrar Pausa, tente novamente!!!'
                ];
            }
        } catch (\Throwable $th) {
            return $data = [
                'class' => 'danger',
                'message' => 'Houve um erro ao cadastrar Pausa, tente novamente!!!'
            ];
        }
    }

    public function edit($attributes)
    {
        try {
            $pausa = $this->pausaModel->orderby('fim', 'desc')->first();

            if ($attributes['inicio'] >= $pausa->fim) {
                $pausa = $this->pausaModel->find($attributes['pausa_id']);
                $attributes['total'] = $this->pontoService->calculaTotal($attributes['inicio'], $attributes['fim']);


                $edited = $pausa->fill($attributes);
                $edited->save();


                if ($edited) {
                    $ponto = [
                        'inicio' => $pausa->ponto->inicio,
                        'fim' => $pausa->ponto->fim,
                        'ponto_id' => $pausa->ponto->id
                    ];

                    $data = $this->pontoService->edit($ponto);

                    if ($data['class'] == 'success') {
                        return $data = [
                            'class' => 'success',
                            'message' => 'Pausa Editada com Sucesso!!!'
                        ];
                    } else {
                        return $data = [
                            'class' => 'danger',
                            'message' => 'Houve um erro ao editar a pausa, tente novamente!!!'
                        ];
                    }
                } else {
                    return $data = [
                        'class' => 'danger',
                        'message' => 'Houve um erro ao editar a pausa, tente novamente!!!'
                    ];
                }
            } else {
                return $data = [
                    'class' => 'danger',
                    'message' => 'Encontramos uma pausa no mesmo horário, por favor, edite novamente para o horário correto!!!'
                ];
            }
        } catch (\Throwable $th) {
            return $data = [
                'class' => 'danger',
                'message' => 'Houve um erro ao editar a pausa, tente novamente!!!'
            ];
        }
    }
}
