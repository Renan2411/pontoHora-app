@extends('layouts.main')

@section('title', 'Categorias')


@section('content')
    <h1>Categorias</h1>

    <p>Suas Categrias Cadastradas estão listadas abaixo</p>

    <div class="col 8">
        @if ($categorias)
            <div class="col-12">
                <div class="accodion mb-3" id="accordionCategorias">
                    @foreach ($categorias as $item)

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $item->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $item->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $item->id }}">
                                    {{ $item->nome }} - Meta : {{ $item->meta }}
                                </button>
                            </h2>

                            <div id="collapse{{ $item->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $item->id }}" data-bs-parent="accordionCategorias">
                                <div class="accordion-body">
                                    @if (isset($ultimasEntradas[$loop->index]))
                                        <form class="ultimasEntradas">

                                            <input type="hidden" name="meta{{ $item->id }}" value="{{ $item->meta }}">

                                            <h5>Última Entrada
                                                @if (isset($ultimasEntradas[$loop->index]))
                                                    <span
                                                        class="ultimaData{{ $item->id }}">{{ date('d/m/y', strtotime($ultimasEntradas[$loop->index]['data'])) }}</span>
                                                @endif
                                            </h5>
                                            <div class="row">
                                                <div class="mb-4 col-6">
                                                    <label for="">Inicio</label>
                                                    <input type="time" class="form-control"
                                                        name="inicio{{ $item->id }}"
                                                        value="{{ $ultimasEntradas[$loop->index]['inicio'] }}" disabled>
                                                </div>

                                                <div class="mb-4 col-6">
                                                    <label for="">Fim</label>
                                                    <input type="time" class="form-control" name="fim{{ $item->id }}"
                                                        value="{{ $ultimasEntradas[$loop->index]['fim'] }}" disabled>
                                                </div>

                                                <div class="mb-4 col-6">
                                                    <label for="">Total</label>
                                                    <input type="time" class="form-control"
                                                        name="total{{ $item->id }}"
                                                        value="{{ $ultimasEntradas[$loop->index]['total'] }}" disabled>
                                                </div>

                                                <div class="mb-4 col-6">
                                                    <label for="">Total de Pausas</label>
                                                    <input type="time" class="form-control"
                                                        name="totalPausas{{ $item->id }}"
                                                        value="{{ $totalPausas[$loop->index] }}" disabled>
                                                </div>


                                                <div class="mb-4 col-12">
                                                    <label for="">Progresso: <a class="btn btn-primary"
                                                            id="updateTime{{ $item->id }}"> <i
                                                                class=" bi bi-arrow-repeat"></i></a></label>
                                                    <div class=" progress mb-4">
                                                        <div class="progress-bar bg-success progressoCategoria{{ $item->id }}"
                                                            role="progressbar" aria-valuenow="70" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 70%">70%
                                                        </div>
                                                    </div>

                                                    @if (!isset($ultimasEntradas[$loop->index]['total']))
                                                        <span
                                                            class="mensagemFinalizacao{{ $item->id }} text-danger"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                    <hr class="bg-success">

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('pontos.index', $item->id) }}"
                                            class="btn btn-info">Visualizar
                                            Entradas</a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoria" type="button">Nova
                Categoria</button>

            <div class="modal fade" id="createCategoria" tabindex="-1" aria-labelledby="createNewCategoria"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createNewCategoria">Nova Categoria</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ route('categoria.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <div class="mb-4">
                                    <label for="nome" class="form-label">Nome: </label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                        placeholder="Nome da Nova Categoria">
                                </div>

                                <div class="mb-4">
                                    <label for="meta" class="form-label">Meta:</label>
                                    <input type="time" class="form-control" id="meta" name="meta">
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                <button class="btn btn-success" type="submit">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @endif
    </div>
@endsection

@section('js')
    <script>
        let forms = document.querySelectorAll("form[class='ultimasEntradas']");
        let names;
        let body = document.querySelector('body');


        for (let i = 1; i <= forms.length; i++) {
            names = 'updateTime' + i;
            let btnProgressBar = document.querySelector(`#${names}`);

            btnProgressBar.addEventListener("click", () => {
                calcularProgresso(i);
            });

            calcularProgresso(i);

        }

        function calcularProgresso(i) {
            let date = new Date();

            //Pegando o Span para lembrar a pessoa de finalizar o ponto;
            let mensagemFinalizacao = document.querySelector(`.mensagemFinalizacao${i}`);

            names = "progressoCategoria" + i;
            let progressBar = document.querySelector(`.${names}`);

            names = 'totalPausas' + i;
            let totalPausas = document.querySelector(`input[name=${names}]`);
            totalPausas = converterHoraMinutos(totalPausas.value);

            names = 'total' + i;
            let horaTotal = document.querySelector(`input[name=${names}]`);

            names = 'meta' + i;
            let metaCategoria = document.querySelector(`input[name=${names}]`);

            if (horaTotal.value != "") {

                if (horaTotal.value >= metaCategoria.value) {
                    progressBar.style.width = "100%";
                    progressBar.textContent = '100%';

                }
            } else {
                //Pegando a ultima data dos Pontos ferentes a cada categoria e transformando em array
                let ultimaData = document.querySelector(`.ultimaData${i}`);
                ultimaData = ultimaData.textContent.split('/');

                if (!verificarData(ultimaData)) {
                    //Pegando as horas e minutos da meta
                    let tempoMeta = converterHoraMinutos(metaCategoria.value);

                    //Pegando a Hora e os minutos atuais
                    let horaAtual = date.getHours();
                    let minutosAtual = date.getMinutes();

                    //Pegando a horário de inicio do ponto
                    names = 'inicio' + i;
                    let pontoInicio = document.querySelector(`input[name=${names}]`);

                    //Separando o array para pegar as horas e os minutos
                    pontoInicio = converterHoraMinutos(pontoInicio.value);

                    tempoAtual = (horaAtual * 60 + minutosAtual) - pontoInicio - totalPausas;

                    let porcentagem = (tempoAtual * 100) / tempoMeta;

                    if (porcentagem >= 100) {
                        porcentagem = 100;

                        if (mensagemFinalizacao) {
                            mensagemFinalizacao.textContent = "Meta Alcançada, Finalize seu Ponto";
                        }
                    }

                    progressBar.style.width = `${porcentagem.toFixed(2)}%`;
                    progressBar.textContent = `${porcentagem.toFixed(2)}%`;

                    console.log(porcentagem.toFixed(2));
                } else {
                    progressBar.style.width = "100%";
                    progressBar.textContent = '100%';

                    if (mensagemFinalizacao) {
                        mensagemFinalizacao.textContent = "Meta Alcançada, Finalize seu Ponto";
                    }
                }
            }
        }

        //Faz a conversão da hora para minutos
        function converterHoraMinutos(tempo) {
            tempo = tempo.toString().split(':');
            tempo = Number(tempo[0]) * 60 + Number(tempo[1]);
            return tempo;
        }

        //Faz a comparação para ver se a data atual é igual ou maior que a que foi cadastrada no ponto
        function verificarData(ultimaData) {
            let date = new Date();

            dia = date.getDate();
            mes = date.getMonth() + 1;
            ano = date.getYear() - 100;

            if (ultimaData[2] < ano) {
                return true;
            }

            if (ultimaData[1] < mes) {
                return true;
            }

            if (ultimaData[0] < dia) {
                return true;
            }

            return false;
        }
    </script>
@endsection
