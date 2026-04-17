<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Ponto de Coleta</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/CadastroFarmaceutico.css') }}">
</head>

<body>

<main class="page">
<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para pagina inicial">
    <span class="seta" aria-hidden="true">&larr;</span>
    <span>Voltar</span>
</a>
<h1 class="titulo">Cadastro de Ponto de Coleta</h1>

<form class="container" method="POST" action="{{ route('cadastro-ponto-coleta.store') }}">
@csrf

@if ($errors->any())
<div class="alerta erro" role="alert" aria-live="polite">
    <strong>Verifique os campos obrigatorios.</strong>
</div>
@endif

@if (session('success'))
<div class="alerta sucesso" role="status" aria-live="polite">
    {{ session('success') }}
</div>
@endif

<label for="razao-social">Razao Social</label>
<input id="razao-social" name="razao_social" type="text" placeholder="Digite a razao social" value="{{ old('razao_social') }}" required>

<label for="nome-fantasia">Nome Fantasia</label>
<input id="nome-fantasia" name="nome_fantasia" type="text" placeholder="Digite o nome fantasia" value="{{ old('nome_fantasia') }}">

<label for="cnpj">CNPJ</label>
<input id="cnpj" name="cnpj" type="text" placeholder="XX.XXX.XXX/XXXX-XX" value="{{ old('cnpj') }}" required>

<label for="horario-funcionamento">Horario de Funcionamento</label>
<input id="horario-funcionamento" name="horario_funcionamento" type="text" placeholder="Ex: 08:00 as 18:00" value="{{ old('horario_funcionamento') }}" required>

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

<label class="checkboxCampo" for="aceita-validade-curta">
    <input id="aceita-validade-curta" name="aceita_validade_curta" type="checkbox" value="1" {{ old('aceita_validade_curta') ? 'checked' : '' }}>
    <span>Aceita medicamentos com validade curta (7 dias)</span>
</label>

<label for="status-exibicao">Status inicial</label>
<input id="status-exibicao" type="text" value="Aprovado" readonly>

<button class="finalizar" type="submit">Finalizar Cadastro</button>

</form>

</main>

</body>
</html>
