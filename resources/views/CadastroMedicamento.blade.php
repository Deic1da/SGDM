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

<div class="grid">


<div class="medicamento">

<label>Nome do Medicamento</label>
<input type="text" placeholder="XXXXXXXX">

<div class="tipo">
<button type="button" class="tipoOpcao" aria-pressed="false">Comprimido</button>
<button type="button" class="tipoOpcao" aria-pressed="false">Cápsula</button>
<button type="button" class="tipoOpcao" aria-pressed="false">Xarope</button>
</div>

<div class="check">
<input type="checkbox" id="lacrado">
<label for="lacrado">Lacrado</label>
</div>

<label>Condição da Embalagem</label>
<p class="exemplo">ex: “Cartela com 5 de 10 comprimidos”</p>
<input type="text" placeholder="XXXXXXXX">

</div>


<div class="data-validade">

<label>Data de Validade</label>
<input type="date">

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

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const botoesTipo = document.querySelectorAll('.tipo .tipoOpcao');

	botoesTipo.forEach(function (botao) {
		botao.addEventListener('click', function () {
			botoesTipo.forEach(function (item) {
				item.classList.remove('ativo');
				item.setAttribute('aria-pressed', 'false');
			});

			botao.classList.add('ativo');
			botao.setAttribute('aria-pressed', 'true');
		});
	});
});
</script>

</body>
</html>