<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
    <link href="{{ asset('css/PaginaInicial.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <div>
            <h1 class="Logo">SGDM</h1>
        </div>
        <div>
            <button class="btnConfig">&#9881;</button>
            <button class="btnPerfil">&#128100;</button>
        </div>
    </header>
        <nav>
            <div class="navEsquerda">
                <input type="text" placeholder="Pesquisar Medicamento">
                <button class="roxo">Doar Medicamento</button>
            </div>
            <div class="navDireita">
                <button class="cinza">Área do Farmacêutico</button>
                <button class="cinza">Gerenciar Ponto de Coleta</button>
            </div>
        </nav>

    <main>
        <div class="Mapa">

        </div>

        <div class="listaPontos">
            <h2>Pontos de Coleta Próximos</h2>
            <div class="listaPontosItens">

                <div class="PontoColeta ativo">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <p>3Km</p>
                </div>
            </div>
        </div>

    </main>
</body>
</html>