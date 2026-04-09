<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Validar Doação</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/ValidarDoacao.css') }}">
</head>

<body>

<a class="voltarInicio" href="{{ route('pagina-inicial') }}" aria-label="Voltar para página inicial">
<span class="seta" aria-hidden="true">&larr;</span>
<span>Voltar</span>
</a>

<main class="paginaValidacao">

<h1 class="titulo">Validar Doação</h1>

<div class="busca">
<input type="text" placeholder="Pesquisar Nº da Doação" aria-label="Pesquisar numero da doacao">
</div>

<div class="container">

<div class="dados-medicamento">

<h3>Dados do Medicamento</h3>

<label>Nome do Medicamento</label>
<input type="text" placeholder="XXXXXXXX">

<div class="tipo">
<button type="button" class="tipoOpcao ativo" aria-pressed="true">Comprimido</button>
<button type="button" class="tipoOpcao" aria-pressed="false">Cápsula</button>
<button type="button" class="tipoOpcao" aria-pressed="false">Xarope</button>
</div>

<div class="check">
<input type="checkbox" checked>
<span>Lacrado</span>
</div>

<label>Data de Validade</label>
<input type="date">

<label>Condição da Embalagem</label>
<input type="text" placeholder="Ex: Cartela com 5 de 10 comprimidos">

</div>

<div class="dados-doador">

<h3>Dados do Doador</h3>

<label>CPF do Doador</label>
<input id="cpfDoador" type="text" placeholder="XXX.XXX.XXX-XX">

<div class="botoes">
<button class="aprovar" id="btnAprovar" disabled aria-disabled="true">Aprovar</button>
<button class="reprovar" id="btnReprovar" disabled aria-disabled="true">Reprovar</button>
</div>

<textarea id="motivoReprovacao" placeholder="Motivo da Reprovação"></textarea>

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const botoesTipo = document.querySelectorAll('.tipo .tipoOpcao');
	const cpfDoador = document.getElementById('cpfDoador');
	const btnAprovar = document.getElementById('btnAprovar');
	const motivoReprovacao = document.getElementById('motivoReprovacao');
	const btnReprovar = document.getElementById('btnReprovar');

	if (!cpfDoador || !btnAprovar || !motivoReprovacao || !btnReprovar) return;

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

	const atualizarEstadoAprovar = function () {
		const cpfSomenteNumeros = cpfDoador.value.replace(/\D/g, '');
		const cpfCompleto = cpfSomenteNumeros.length === 11;
		btnAprovar.disabled = !cpfCompleto;
		btnAprovar.setAttribute('aria-disabled', String(!cpfCompleto));
	};

	const atualizarEstadoReprovar = function () {
		const temMotivo = motivoReprovacao.value.trim().length > 0;
		btnReprovar.disabled = !temMotivo;
		btnReprovar.setAttribute('aria-disabled', String(!temMotivo));
	};

	cpfDoador.addEventListener('input', atualizarEstadoAprovar);
	motivoReprovacao.addEventListener('input', atualizarEstadoReprovar);
	atualizarEstadoAprovar();
	atualizarEstadoReprovar();
});
</script>

</main>

</body>
</html>