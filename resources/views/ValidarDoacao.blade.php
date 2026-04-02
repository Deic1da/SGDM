<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Validar Doação</title>
<link rel="stylesheet" href="{{ asset('css/ValidarDoacao.css') }}">
</head>

<body>

<h1 class="titulo">Validar Doação</h1>

<div class="busca">
<input type="text" placeholder="Pesquisar Nº da Doação">
</div>

<div class="container">

<div class="dados-medicamento">

<h3>Dados do Medicamento</h3>

<label>Nome do Medicamento</label>
<input type="text" placeholder="XXXXXXXX">

<div class="tipo">
<span class="ativo">Comprimido</span>
<span>Cápsula</span>
<span>Xarope</span>
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
<input type="text" placeholder="XXX.XXX.XXX-XX">

<div class="botoes">
<button class="aprovar">Aprovar</button>
<button class="reprovar">Reprovar</button>
</div>

<textarea placeholder="Motivo da Reprovação"></textarea>

</div>

</div>

</body>
</html>