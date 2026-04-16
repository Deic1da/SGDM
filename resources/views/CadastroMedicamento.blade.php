<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Medicamento</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/CadastroMedicamento.css') }}">
</head>

<body>

<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para página inicial">
<span class="seta" aria-hidden="true">&larr;</span>
<span>Voltar</span>
</a>

<div class="container">

<h1>Cadastrar Medicamento</h1>

@if (session('success'))
<div class="feedback sucesso">{{ session('success') }}</div>
@endif

@if ($errors->any())
<div class="feedback erro">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('cadastro-medicamento.store') }}" method="post">
@csrf

<div class="grid">


<div class="medicamento">

<label>Nome do Medicamento</label>
<input type="text" name="nome_medicamento" placeholder="XXXXXXXX" value="{{ old('nome_medicamento') }}">

<div class="tipo" data-old-forma="{{ old('forma_farmaceutica') }}">
<button type="button" class="tipoOpcao" data-value="Comprimido" aria-pressed="false">Comprimido</button>
<button type="button" class="tipoOpcao" data-value="Cápsula" aria-pressed="false">Cápsula</button>
<button type="button" class="tipoOpcao" data-value="Xarope" aria-pressed="false">Xarope</button>
</div>
<input type="hidden" name="forma_farmaceutica" id="formaFarmaceutica" value="{{ old('forma_farmaceutica') }}">
@error('forma_farmaceutica')
<small class="campoErro">{{ $message }}</small>
@enderror

<div class="check">
<input type="checkbox" id="lacrado" name="lacrado" value="1" {{ old('lacrado') ? 'checked' : '' }}>
<label for="lacrado">Lacrado</label>
</div>

<label>Condição da Embalagem</label>
<p class="exemplo">ex: “Cartela com 5 de 10 comprimidos”</p>
<input type="text" name="condicao_embalagem" placeholder="XXXXXXXX" value="{{ old('condicao_embalagem') }}">

</div>


<div class="data-validade">

<label>Data de Validade</label>
<input type="date" name="data_validade" value="{{ old('data_validade') }}">

<label class="uploadTitulo">Imagem do Medicamento</label>
<div class="uploadImagem" role="button" aria-label="Área de upload de imagem">
<span class="uploadIcone" aria-hidden="true">+</span>
<p>Arraste uma imagem aqui</p>
<small>ou clique para selecionar</small>
</div>

</div>


<div class="pontos-coleta">

<h3>Pontos de Coleta Próximos</h3>

<div class="ponto"><span>Ponto de Coleta</span><span>3Km</span></div>
<div class="ponto"><span>Ponto de Coleta</span><span>3Km</span></div>
<div class="ponto"><span>Ponto de Coleta</span><span>3Km</span></div>
<div class="ponto"><span>Ponto de Coleta</span><span>3Km</span></div>
<div class="ponto"><span>Ponto de Coleta</span><span>3Km</span></div>

</div>

</div>

<button class="finalizar">Finalizar Cadastro</button>

</form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const botoesTipo = document.querySelectorAll('.tipo .tipoOpcao');
	const formaFarmaceutica = document.getElementById('formaFarmaceutica');
	const tipoContainer = document.querySelector('.tipo');
	const formaAntiga = tipoContainer ? tipoContainer.dataset.oldForma : '';

	const selecionarTipo = function (botaoSelecionado) {
		botoesTipo.forEach(function (item) {
			item.classList.remove('ativo');
			item.setAttribute('aria-pressed', 'false');
		});

		botaoSelecionado.classList.add('ativo');
		botaoSelecionado.setAttribute('aria-pressed', 'true');
		if (formaFarmaceutica) {
			formaFarmaceutica.value = botaoSelecionado.dataset.value || '';
		}
	};

	botoesTipo.forEach(function (botao) {
		botao.addEventListener('click', function () {
			selecionarTipo(botao);
		});
	});

	if (formaAntiga) {
		const botaoInicial = Array.from(botoesTipo).find(function (botao) {
			return botao.dataset.value === formaAntiga;
		});
		if (botaoInicial) {
			selecionarTipo(botaoInicial);
		}
	}
});
</script>

</body>
</html>