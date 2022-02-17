@extends('layouts.main')

@section('title', 'Pontos')

@section('content')
    <h1>Pontos</h1>

    <a href="{{ route('categorias.index') }}" class="btn btn-success">Voltar</a>

    <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#createPonto">Adicionar</button>

    <table class="table table-striped col-8 text-center">
        <thead>
            <tr>
                <th scope="row">Data</th>
                <th scope="row">Inicio</th>
                <th scope="row">Fim</th>
                <th scope="row">Total</th>
                <th scope="row">Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pontos as $item)
                <tr>
                    <td>{{ date('d/m/y', strtotime($item->data)) }}</td>
                    <td>{{ $item->inicio }}</td>
                    <td>
                        @if ($item->fim)
                            {{ $item->fim }}
                        @else
                            Ponto Não Finalizado
                        @endif
                    </td>
                    <td>
                        @if ($item->total)
                            {{ $item->total }}
                        @else
                            Ponto Não Finalizado
                        @endif
                    </td>

                    <td>

                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                            data-bs-target="#viewPausas{{ $item->id }}">
                            <i class="bi bi-eye-fill"></i>
                        </button>

                        <button type="button" class="btn btn-info addPausa" data-bs-toggle="modal"
                            data-bs-target="#pausa{{ $item->id }}">
                            <i class="bi bi-stopwatch-fill"></i>
                        </button>

                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editPonto{{ $item->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </td>
                </tr>

                <div class="modal fade" id="viewPausas{{ $item->id }}" tabindex="-1"
                    aria-labelledby="visualizarPausas{{ $item->id }}" aria-hidden="true">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="visualizarPausas{{ $item->id }}">
                                    Pausas Ponto  
                                    {{ date('d/m/y', strtotime($item->data)) }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="close"></button>
                            </div>

                            <div class="modal-body">

                                @foreach ($item->pausas as $pausa)
                                    <hr class="text-success">

                                    <form action="{{route('pausa.update')}}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="pausa_id" value="{{ $pausa->id }}">

                                        <div class="row">
                                            <div class="mb-4 col-4">
                                                <label for="inicio" class="form-label">Inicio</label>
                                                <input type="time" name="inicio" id="inicio" class="form-control"
                                                    value="{{ $pausa->inicio }}">
                                            </div>

                                            <div class="mb-4 col-4">
                                                <label for="fim" class="form-label">Fim</label>
                                                <input type="time" name="fim" id="fim" class="form-control"
                                                    value="{{ $pausa->fim }}">
                                            </div>

                                            <div class="mb-4 col-4">
                                                <label for="total" class="form-label">Total</label>
                                                <input type="time" name="total" id="total" class="form-control"
                                                    value="{{ $pausa->total }}" disabled>
                                            </div>

                                            <div class="mb-4 col-12 mt-4">
                                                <button type="submit" class="btn btn-warning" style="width: 100%">
                                                    Editar
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                @endforeach

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal fade" id="pausa{{ $item->id }}" tabindex="-1"
                    aria-labelledby="adicionarPausa{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="adicionarPausa{{ $item->id }}">Adicionar Pausa Ponto
                                    {{ date('d/m/y', strtotime($item->data)) }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="close"></button>
                            </div>

                            <form action="{{ route('pausa.store') }}" method="POST">
                                @csrf

                                <div class="modal-body">
                                    <input type="hidden" name="ponto_id" value="{{ $item->id }}">

                                    <div class="row">
                                        <div class="mb-4 col-6">
                                            <label for="inicio" class="form-label">Inicio</label>
                                            <input type="time" name="inicio" id="inicio" class="form-control"
                                                min="{{ $item->inicio }}" max="{{ $item->fim }}">
                                        </div>
                                        <div class="mb-4 col-6">
                                            <label for="fim" class="form-label">Fim</label>
                                            <input type="time" name="fim" id="fim" class="form-control"
                                                min="{{ $item->inicio }}" max="{{ $item->fim }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-info">Adicionar Pausa</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editPonto{{ $item->id }}" tabindex="-1"
                    aria-labelledby="editModalPonto{{ $item->id }}" aria-hidden="true">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalPonto{{ $item->id }}">Editar Ponto
                                    {{ date('d/m/y', strtotime($item->data)) }}</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="close"></button>
                            </div>

                            <form action="{{ route('ponto.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" value="{{ $item->id }}" name="ponto_id">

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-4 col-6">
                                            <label for="inicio" class="form-label">Inicio</label>
                                            <input type="time" name="inicio" id="inicio" class="form-control"
                                                value="{{ $item->inicio }}">
                                        </div>
                                        <div class="mb-4 col-6">
                                            <label for="fim" class="form-label">Fim</label>
                                            <input type="time" name="fim" id="fim" class="form-control"
                                                value="{{ $item->fim }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button class="btn btn-warning">Editar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </tbody>
    </table>


    <div class="modal fade" id="createPonto" tabindex="-1" aria-labelledby="CriacaoDePonto" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CriacaoDePonto">Novo Ponto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('ponto.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="categoria_id" value="{{ $categoria_id }}">
                    <div class="modal-body">

                        <div class="row">
                            <div class="mb-4 col-12">
                                <label for="data" class="form-label">Data</label>
                                <input type="date" class="form-control" name="data" id="data">
                            </div>

                            <div class="mb-4 col-6">
                                <label for="inicio" class="form-label">Inicio</label>
                                <input type="time" class="form-control" name="inicio" id="inicio">
                            </div>

                            <div class="mb-4 col-6">
                                <label for="inicio" class="form-label">Fim</label>
                                <input type="time" class="form-control" name="fim" id="fim">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        let btnPausa = document.querySelectorAll('.addPausa');

        btnPausa.forEach(element => {
            element.addEventListener('clicke', (e) => {
                console.log(e.target);
            });
        });
    </script>
@endsection
