<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Estoque de Medicamentos</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/EstoqueMedicamento.css') }}">
</head>

<body>

<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para pagina inicial">
    <span class="setaIcone" aria-hidden="true">&larr;</span>
    <span>Voltar</span>
</a>

@if (session('success'))
<div class="flash sucesso">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="flash erro">{{ session('error') }}</div>
@endif

@if ($errors->any())
<div class="flash erro">{{ $errors->first() }}</div>
@endif

<div class="container">

    <div class="topoSecao">
        <h2>Estoque de Medicamentos</h2>
        <span>{{ $entidade->nome_fantasia ?: $entidade->razao_social }}</span>
        <form class="formPesquisaEstoque" id="formPesquisaEstoque" method="GET" action="{{ route('estoque-medicamento', $entidade->id) }}" autocomplete="off">
            <div class="pesquisaAutocomplete">
                <input
                    class="pesquisa"
                    id="inputPesquisaEstoque"
                    type="text"
                    name="medicamento"
                    value="{{ $termoMedicamento }}"
                    placeholder="Pesquisar Medicamento"
                    aria-label="Pesquisar medicamento"
                    aria-controls="sugestoesEstoque"
                    aria-expanded="false"
                    data-sugestoes-url="{{ route('estoque-medicamento.sugestoes', $entidade->id) }}"
                    autocomplete="off"
                >
                <div class="sugestoesEstoque" id="sugestoesEstoque" role="listbox" hidden></div>
            </div>
            <button class="btnPesquisar" type="submit">Pesquisar</button>
        </form>
    </div>

    <div class="conteudo">

        <div class="dados">
            <table>
                <thead>
                    <tr>
                        <th>Selecionar</th>
                        <th>Medicamento</th>
                        <th>Tipo</th>
                        <th>Qtd</th>
                        <th>Validade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($itens as $item)
                @php
                    $statusClasse = match ($item->status_estoque) {
                        'Disponivel' => 'disponivel',
                        'Reservado' => 'reservado',
                        'Entregue' => 'entregue',
                        'Descartado' => 'descartado',
                        default => 'disponivel',
                    };

                    $statusTexto = match ($item->status_estoque) {
                        'Disponivel' => 'Disponivel',
                        'Reservado' => 'Reservado',
                        'Entregue' => 'Entregue',
                        'Descartado' => 'Descartado',
                        default => $item->status_estoque,
                    };

                    $podeEntregar = in_array($item->status_estoque, ['Disponivel', 'Reservado'], true) && $item->quantidade > 0;
                @endphp
                <tr class="{{ $podeEntregar ? 'linhaSelecionavel' : 'linhaBloqueada' }}" data-medicamento="{{ $item->nome_medicamento }}">
                    <td>
                        <input
                            class="radioEstoque"
                            type="radio"
                            name="estoque_id"
                            value="{{ $item->id }}"
                            form="formRegistrarEntrega"
                            aria-label="Selecionar {{ $item->nome_medicamento }}"
                            @disabled(!$podeEntregar)
                        >
                    </td>
                    <td>{{ $item->nome_medicamento }}</td>
                    <td>{{ $item->forma_farmaceutica }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->data_validade)->format('d/m/Y') }}</td>
                    <td><span class="status {{ $statusClasse }}">{{ $statusTexto }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Nenhum medicamento encontrado para a entidade vinculada.</td>
                </tr>
                @endforelse

                </tbody>

            </table>

        </div>

        <div class="formulario">

            <h3>Registrar Entrega</h3>
            <p class="medicamentoSelecionado" id="medicamentoSelecionado">Selecione um medicamento na tabela.</p>

            <form method="POST" action="{{ route('estoque-medicamento.entregar', $entidade->id) }}" id="formRegistrarEntrega">
                @csrf

                <label for="cpfDestinatario">CPF do Destinatario</label>
                <input id="cpfDestinatario" name="cpf" type="text" value="{{ old('cpf') }}" placeholder="XXX.XXX.XXX-XX">

                <button class="entrega" type="submit">Registrar Entrega</button>
            </form>

        </div>

    </div>

    <div class="paginacao">
        @if ($itens->hasPages())
            @if ($itens->onFirstPage())
                <span class="seta">&lt;</span>
            @else
                <a class="seta" href="{{ $itens->previousPageUrl() }}">&lt;</a>
            @endif

            <span class="pagina ativa">{{ $itens->currentPage() }}</span>
            <span class="pontos">de {{ $itens->lastPage() }}</span>

            @if ($itens->hasMorePages())
                <a class="seta" href="{{ $itens->nextPageUrl() }}">&gt;</a>
            @else
                <span class="seta">&gt;</span>
            @endif
        @endif
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const linhas = document.querySelectorAll('.linhaSelecionavel');
        const radios = document.querySelectorAll('.radioEstoque');
        const medicamentoSelecionado = document.getElementById('medicamentoSelecionado');
        const inputPesquisaEstoque = document.getElementById('inputPesquisaEstoque');
        const formPesquisaEstoque = document.getElementById('formPesquisaEstoque');
        const sugestoesEstoque = document.getElementById('sugestoesEstoque');
        let timeoutSugestoes = null;
        let requisicaoSugestoes = null;

        const atualizarSelecionado = function (radio) {
            const linha = radio.closest('tr');

            radios.forEach(function (item) {
                item.closest('tr')?.classList.remove('selecionado');
            });

            linha.classList.add('selecionado');

            if (medicamentoSelecionado) {
                medicamentoSelecionado.textContent = 'Selecionado: ' + linha.dataset.medicamento;
            }
        };

        linhas.forEach(function (linha) {
            linha.addEventListener('click', function (event) {
                const radio = linha.querySelector('.radioEstoque');

                if (!radio || radio.disabled || event.target.tagName === 'INPUT') return;

                radio.checked = true;
                atualizarSelecionado(radio);
            });
        });

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                atualizarSelecionado(radio);
            });

            if (radio.checked) {
                atualizarSelecionado(radio);
            }
        });

        const esconderSugestoes = function () {
            if (!sugestoesEstoque || !inputPesquisaEstoque) return;

            sugestoesEstoque.innerHTML = '';
            sugestoesEstoque.setAttribute('hidden', 'hidden');
            inputPesquisaEstoque.setAttribute('aria-expanded', 'false');
        };

        const enviarPesquisa = function (nomeMedicamento) {
            if (!formPesquisaEstoque || !inputPesquisaEstoque) return;

            inputPesquisaEstoque.value = nomeMedicamento;
            esconderSugestoes();
            formPesquisaEstoque.submit();
        };

        const renderizarSugestoes = function (sugestoes) {
            if (!sugestoesEstoque || !inputPesquisaEstoque) return;

            sugestoesEstoque.innerHTML = '';

            if (!Array.isArray(sugestoes) || sugestoes.length === 0) {
                esconderSugestoes();
                return;
            }

            sugestoes.forEach(function (nomeMedicamento) {
                const botao = document.createElement('button');
                botao.type = 'button';
                botao.className = 'sugestaoEstoque';
                botao.setAttribute('role', 'option');
                botao.textContent = nomeMedicamento;
                botao.addEventListener('click', function () {
                    enviarPesquisa(nomeMedicamento);
                });

                sugestoesEstoque.appendChild(botao);
            });

            sugestoesEstoque.removeAttribute('hidden');
            inputPesquisaEstoque.setAttribute('aria-expanded', 'true');
        };

        const buscarSugestoes = function () {
            if (!inputPesquisaEstoque || !sugestoesEstoque) return;

            const termo = inputPesquisaEstoque.value.trim();

            if (termo.length < 2) {
                esconderSugestoes();
                return;
            }

            if (requisicaoSugestoes) {
                requisicaoSugestoes.abort();
            }

            requisicaoSugestoes = new AbortController();

            const url = new URL(inputPesquisaEstoque.dataset.sugestoesUrl, window.location.origin);
            url.searchParams.set('termo', termo);

            fetch(url.toString(), {
                headers: {
                    Accept: 'application/json'
                },
                signal: requisicaoSugestoes.signal
            })
                .then(function (response) {
                    if (!response.ok) throw new Error('Erro ao buscar sugestoes.');
                    return response.json();
                })
                .then(function (sugestoes) {
                    if (inputPesquisaEstoque.value.trim() !== termo) return;
                    renderizarSugestoes(sugestoes);
                })
                .catch(function (error) {
                    if (error.name === 'AbortError') return;
                    esconderSugestoes();
                });
        };

        if (inputPesquisaEstoque) {
            inputPesquisaEstoque.addEventListener('input', function () {
                window.clearTimeout(timeoutSugestoes);
                timeoutSugestoes = window.setTimeout(buscarSugestoes, 300);
            });

            inputPesquisaEstoque.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    esconderSugestoes();
                }
            });
        }

        document.addEventListener('click', function (event) {
            if (!sugestoesEstoque || !inputPesquisaEstoque) return;

            const clicouNaBusca = inputPesquisaEstoque.contains(event.target)
                || sugestoesEstoque.contains(event.target);

            if (!clicouNaBusca) {
                esconderSugestoes();
            }
        });
    });
</script>

</body>
</html>
