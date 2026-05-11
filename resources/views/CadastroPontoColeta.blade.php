<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Ponto de Coleta</title>
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

<h1>Cadastro de Ponto de Coleta</h1>

@if (session('success'))
<div class="feedback sucesso" role="status" aria-live="polite">{{ session('success') }}</div>
@endif

@if ($errors->any())
<div class="feedback erro" role="alert" aria-live="polite">
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
</div>
@endif

<form action="{{ route('cadastro-ponto-coleta.store') }}" method="POST">
@csrf

<div class="grid">

    <div class="medicamento">
        <label for="razao-social">Razao Social</label>
        <input id="razao-social" name="razao_social" type="text" placeholder="Digite a razao social" value="{{ old('razao_social') }}" required>

        <label for="nome-fantasia">Nome Fantasia</label>
        <input id="nome-fantasia" name="nome_fantasia" type="text" placeholder="Digite o nome fantasia" value="{{ old('nome_fantasia') }}">

        <label for="cnpj">CNPJ</label>
        <input id="cnpj" name="cnpj" type="text" placeholder="XX.XXX.XXX/XXXX-XX" value="{{ old('cnpj') }}" required>

        <label for="horario-abertura">Horario de Funcionamento</label>
        <p class="exemplo">Selecione o horario de abertura e fechamento.</p>
        @php
            $horario = (string) old('horario_funcionamento');
            $partesHorario = array_map('trim', explode(' as ', $horario));
            $aberturaPartes = isset($partesHorario[0]) ? explode(':', $partesHorario[0]) : [];
            $fechamentoPartes = isset($partesHorario[1]) ? explode(':', $partesHorario[1]) : [];
            $horaAbertura = $aberturaPartes[0] ?? '';
            $minutoAbertura = $aberturaPartes[1] ?? '';
            $horaFechamento = $fechamentoPartes[0] ?? '';
            $minutoFechamento = $fechamentoPartes[1] ?? '';
            $horas = range(0, 23);
            $minutos = range(0, 59);
        @endphp
        <div class="horario-grid">
            <div>
                <label for="horario-abertura">Abertura</label>
                <div class="horario-selects">
                    <select id="horario-abertura-hora" required>
                        <option value="" @if ($horaAbertura === '') selected @endif disabled>Hora</option>
                        @foreach ($horas as $hora)
                            @php $horaFormatada = sprintf('%02d', $hora); @endphp
                            <option value="{{ $horaFormatada }}" @if ($horaAbertura === $horaFormatada) selected @endif>{{ $horaFormatada }}</option>
                        @endforeach
                    </select>
                    <select id="horario-abertura-minuto" required>
                        <option value="" @if ($minutoAbertura === '') selected @endif disabled>Min</option>
                        @foreach ($minutos as $minuto)
                            @php $minutoFormatado = sprintf('%02d', $minuto); @endphp
                            <option value="{{ $minutoFormatado }}" @if ($minutoAbertura === $minutoFormatado) selected @endif>{{ $minutoFormatado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="horario-fechamento">Fechamento</label>
                <div class="horario-selects">
                    <select id="horario-fechamento-hora" required>
                        <option value="" @if ($horaFechamento === '') selected @endif disabled>Hora</option>
                        @foreach ($horas as $hora)
                            @php $horaFormatada = sprintf('%02d', $hora); @endphp
                            <option value="{{ $horaFormatada }}" @if ($horaFechamento === $horaFormatada) selected @endif>{{ $horaFormatada }}</option>
                        @endforeach
                    </select>
                    <select id="horario-fechamento-minuto" required>
                        <option value="" @if ($minutoFechamento === '') selected @endif disabled>Min</option>
                        @foreach ($minutos as $minuto)
                            @php $minutoFormatado = sprintf('%02d', $minuto); @endphp
                            <option value="{{ $minutoFormatado }}" @if ($minutoFechamento === $minutoFormatado) selected @endif>{{ $minutoFormatado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <input id="horario-funcionamento" name="horario_funcionamento" type="hidden" value="{{ old('horario_funcionamento') }}" required>

        <div class="check">
            <input id="aceita-validade-curta" name="aceita_validade_curta" type="checkbox" value="1" {{ old('aceita_validade_curta') ? 'checked' : '' }}>
            <label for="aceita-validade-curta">Aceita medicamentos com validade curta (7 dias)</label>
        </div>

    </div>

    <div class="data-validade">
        <label for="cep">CEP</label>
        <input id="cep" name="cep" type="text" placeholder="XXXXX-XXX" value="{{ old('cep') }}" required>

        <label for="logradouro">Logradouro</label>
        <input id="logradouro" name="logradouro" type="text" placeholder="Rua, avenida, etc." value="{{ old('logradouro') }}" required>

        <label for="numero">Numero</label>
        <input id="numero" name="numero" type="text" placeholder="Numero" value="{{ old('numero') }}" required>

        <label for="bairro">Bairro</label>
        <input id="bairro" name="bairro" type="text" placeholder="Bairro" value="{{ old('bairro') }}" required>

        <label for="municipio">Municipio</label>
        <input id="municipio" name="municipio" type="text" placeholder="Municipio" value="{{ old('municipio') }}" required>

        <label for="estado">Estado</label>
        <input id="estado" name="estado" type="text" placeholder="UF" maxlength="2" value="{{ old('estado') }}" required>
    </div>

    <div class="pontos-coleta">
        <h3>Localizacao</h3>
        <x-mapa-ponto-coleta />
    </div>

</div>

<button class="finalizar" type="submit">Finalizar Cadastro</button>

</form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const aberturaHora = document.getElementById('horario-abertura-hora');
        const aberturaMinuto = document.getElementById('horario-abertura-minuto');
        const fechamentoHora = document.getElementById('horario-fechamento-hora');
        const fechamentoMinuto = document.getElementById('horario-fechamento-minuto');
        const campoFinal = document.getElementById('horario-funcionamento');

        const sincronizarHorario = function () {
            const inicio = aberturaHora && aberturaMinuto
                ? `${aberturaHora.value}:${aberturaMinuto.value}`
                : '';
            const fim = fechamentoHora && fechamentoMinuto
                ? `${fechamentoHora.value}:${fechamentoMinuto.value}`
                : '';
            if (campoFinal) {
                campoFinal.value = inicio && fim ? `${inicio} as ${fim}` : '';
            }
        };

        if (aberturaHora && aberturaMinuto && fechamentoHora && fechamentoMinuto) {
            aberturaHora.addEventListener('change', sincronizarHorario);
            aberturaMinuto.addEventListener('change', sincronizarHorario);
            fechamentoHora.addEventListener('change', sincronizarHorario);
            fechamentoMinuto.addEventListener('change', sincronizarHorario);
            sincronizarHorario();
        }
    });
</script>

</body>
</html>
