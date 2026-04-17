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

<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para página inicial">
    <span class="setaIcone" aria-hidden="true">&larr;</span>
    <span>Voltar</span>
</a>

<div class="container">

    <div class="topoSecao">
        <h2>Estoque de Medicamentos</h2>
        <input class="pesquisa" type="text" placeholder="Pesquisar Medicamento" aria-label="Pesquisar medicamento">
    </div>

    <div class="conteudo">

        <div class="dados">
            <table>
                <thead>
                    <tr>
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
                        'Disponivel' => 'Disponível',
                        'Reservado' => 'Reservado',
                        'Entregue' => 'Entregue',
                        'Descartado' => 'Descartado',
                        default => $item->status_estoque,
                    };
                @endphp
                <tr class="{{ $loop->first ? 'destaque' : '' }}">
                    <td>{{ $item->nome_medicamento }}</td>
                    <td>{{ $item->forma_farmaceutica }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->data_validade)->format('d/m/Y') }}</td>
                    <td><span class="status {{ $statusClasse }}">{{ $statusTexto }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Nenhum medicamento encontrado para a entidade vinculada.</td>
                </tr>
                @endforelse

                </tbody>

            </table>

        </div>

        <div class="formulario">

            <h3>Registrar Entrega</h3>

            <label>CPF do Destinatário</label>
            <input type="text" placeholder="XXX.XXX.XXX-XX">

            <button class="entrega">Registrar Entrega</button>

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

</body>
</html>