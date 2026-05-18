<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Farmaceutico</title>
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
<h1 class="titulo">Cadastro de Farmaceutico</h1>

<form class="container" action="{{ route('cadastro-farmaceutico.store') }}" method="POST">
@csrf

@if ($errors->any())
<div class="alerta erro" role="alert" aria-live="polite">
	<ul>
	@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
	</ul>
</div>
@endif

<label for="crf">Numero do CRF</label>
<input id="crf" name="num_crf" type="text" placeholder="Digite o numero do CRF" value="{{ old('num_crf') }}" required>

<label for="uf-crf">UF do CRF</label>
<select id="uf-crf" name="uf_crf" required>
<option value="">Selecione</option>
@foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
<option value="{{ $uf }}" @selected(old('uf_crf') === $uf)>{{ $uf }}</option>
@endforeach
</select>

<button class="finalizar" type="submit">Finalizar Cadastro</button>

</form>

</main>

</body>
</html>
