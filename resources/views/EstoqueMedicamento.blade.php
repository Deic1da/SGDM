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

                <tr class="destaque">
                    <td>Dipirona</td>
                    <td>Comprimido</td>
                    <td>120</td>
                    <td>17/02/2026</td>
                    <td><span class="status disponivel">Disponível</span></td>
                </tr>

                <tr>
                    <td>Paracetamol</td>
                    <td>Comprimido</td>
                    <td>85</td>
                    <td>15/03/2026</td>
                    <td><span class="status disponivel">Disponível</span></td>
                </tr>

                <tr>
                    <td>Ibuprofeno</td>
                    <td>Cápsula</td>
                    <td>40</td>
                    <td>01/01/2027</td>
                    <td><span class="status reservado">Reservado</span></td>
                </tr>

                <tr>
                    <td>Amoxicilina</td>
                    <td>Suspensão</td>
                    <td>12</td>
                    <td>30/09/2025</td>
                    <td><span class="status descartado">Descartado</span></td>
                </tr>

                <tr>
                    <td>Losartana</td>
                    <td>Comprimido</td>
                    <td>200</td>
                    <td>18/07/2026</td>
                    <td><span class="status disponivel">Disponível</span></td>
                </tr>

                <tr>
                    <td>Omeprazol</td>
                    <td>Cápsula</td>
                    <td>63</td>
                    <td>02/05/2026</td>
                    <td><span class="status reservado">Reservado</span></td>
                </tr>

                <tr>
                    <td>Insulina</td>
                    <td>Injetável</td>
                    <td>25</td>
                    <td>25/12/2025</td>
                    <td><span class="status entregue">Entregue</span></td>
                </tr>

                <tr>
                    <td>Metformina</td>
                    <td>Comprimido</td>
                    <td>150</td>
                    <td>11/08/2026</td>
                    <td><span class="status disponivel">Disponível</span></td>
                </tr>

                <tr>
                    <td>AAS</td>
                    <td>Comprimido</td>
                    <td>90</td>
                    <td>14/12/2026</td>
                    <td><span class="status disponivel">Disponível</span></td>
                </tr>

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
        <span class="seta">&lt;</span>
        <span class="pagina ativa">1</span>
        <span class="pagina">2</span>
        <span class="pagina">3</span>
        <span class="pagina">4</span>
        <span class="pontos">...</span>
        <span class="pagina">40</span>
        <span class="seta">&gt;</span>
    </div>

</div>

</body>
</html>