<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Farmacêutico</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/CadastroFarmaceutico.css') }}">
</head>

<body>

<main class="page">
<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para página inicial">
	<span class="seta" aria-hidden="true">&larr;</span>
	<span>Voltar</span>
</a>
<h1 class="titulo">Cadastro de Farmacêutico</h1>

<div class="container">

<label for="crf">Número do CRF</label>
<input id="crf" type="text" placeholder="Digite o número do CRF">

<label for="uf-crf">UF do CRF</label>
<select id="uf-crf">
<option value="">Selecione</option>
<option>AC</option>
<option>AL</option>
<option>AP</option>
<option>AM</option>
<option>BA</option>
<option>CE</option>
<option>DF</option>
<option>ES</option>
<option>GO</option>
<option>MA</option>
<option>MT</option>
<option>MS</option>
<option>MG</option>
<option>PA</option>
<option>PB</option>
<option>PR</option>
<option>PE</option>
<option>PI</option>
<option>RJ</option>
<option>RN</option>
<option>RS</option>
<option>RO</option>
<option>RR</option>
<option>SC</option>
<option>SP</option>
<option>SE</option>
<option>TO</option>
</select>

<button class="finalizar">Finalizar Cadastro</button>

</div>

</main>

</body>
</html>