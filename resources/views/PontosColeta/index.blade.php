<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meus Pontos de Coleta</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/CadastroMedicamento.css') }}">
</head>

<body class="cadastro-ponto-coleta">
<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para pagina inicial">
    <span class="seta" aria-hidden="true">&larr;</span>
    <span>Voltar</span>
</a>

<div class="container">
    <h1>Meus Pontos de Coleta</h1>

    @if (session('success'))
    <div class="feedback sucesso" role="status" aria-live="polite">{{ session('success') }}</div>
    @endif

    <div class="tabelaCrud">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Endereco</th>
                    <th>Horario</th>
                    <th>Status</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entidades as $entidade)
                <tr>
                    <td>{{ $entidade->nome_fantasia ?: $entidade->razao_social }}</td>
                    <td>{{ $entidade->cnpj }}</td>
                    <td>{{ $entidade->logradouro }}, {{ $entidade->numero }} - {{ $entidade->bairro }}</td>
                    <td>{{ $entidade->horario_funcionamento }}</td>
                    <td><span class="statusBadge">{{ $entidade->status }}</span></td>
                    <td>
                        <div class="acoesLinha">
                            <a href="{{ route('pontos-coleta.edit', $entidade) }}">Editar</a>
                            @if ($entidade->status !== 'Inativo')
                            <form action="{{ route('pontos-coleta.destroy', $entidade) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Desativar</button>
                            </form>
                            @else
                            <form action="{{ route('pontos-coleta.reativar', $entidade) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Reativar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Nenhum ponto de coleta cadastrado ainda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
