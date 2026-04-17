<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Teko:wght@500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/PaginaInicial.css') }}" rel="stylesheet">
</head>
<body>
    <header class="topbar">
        <div class="brandBlock">
            <h1 class="Logo">SGDM</h1>
        </div>
        <div class="topActions" id="perfilMenuContainer">
            <button class="btnConfig is-hidden" aria-hidden="true" tabindex="-1">&#9881;</button>
            <button class="btnPerfil" id="btnPerfil" aria-expanded="false" aria-controls="perfilMenu">&#128100;</button>
            <div class="perfilMenu card" id="perfilMenu" hidden>
                <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('cadastro-farmaceutico') }}'">Cadastro de Farmacêutico</button>
                <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('cadastro-ponto-coleta') }}'">Cadastrar ponto de coleta</button>
                <button type="button" class="perfilMenuItem" aria-label="Trocar foto do perfil">Trocar foto do perfil</button>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="perfilMenuItem">Sair</button>
                </form>
            </div>
        </div>
    </header>

    <nav class="actionBar">
            <div class="navEsquerda">
                <input type="text" placeholder="Pesquisar Medicamento" aria-label="Pesquisar medicamento">
                <button class="roxo" onclick="window.location.href='{{ route('cadastro-medicamento') }}'">Doar Medicamento</button>
            </div>
            <div class="navDireita">
                <button class="{{ $temAcessoFarmaceutico ? 'roxo' : 'cinza' }}" onclick="window.location.href='{{ route('estoque-medicamento') }}'">Gerenciar Remédios</button>
                <button class="{{ $temAcessoFarmaceutico ? 'roxo' : 'cinza' }}" onclick="window.location.href='{{ route('validar-doacao') }}'">Validar Doação</button>
            </div>
    </nav>

    <main class="dashboard">
        <section class="Mapa card"></section>

        <aside class="listaPontos card">
            <h2>Pontos de Coleta Próximos</h2>
            <div class="listaPontosItens">

                <div class="PontoColeta ativo">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
                <div class="PontoColeta">
                    <p>Ponto de Coleta</p>
                    <span>3Km</span>
                </div>
            </div>
        </aside>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pontos = document.querySelectorAll('.listaPontosItens .PontoColeta');
            const btnPerfil = document.getElementById('btnPerfil');
            const perfilMenu = document.getElementById('perfilMenu');
            const perfilMenuContainer = document.getElementById('perfilMenuContainer');

            pontos.forEach(function (ponto) {
                ponto.addEventListener('click', function () {
                    pontos.forEach(function (item) {
                        item.classList.remove('ativo');
                    });

                    ponto.classList.add('ativo');
                });
            });

            if (btnPerfil && perfilMenu && perfilMenuContainer) {
                btnPerfil.addEventListener('click', function () {
                    const isOpen = !perfilMenu.hasAttribute('hidden');

                    if (isOpen) {
                        perfilMenu.setAttribute('hidden', 'hidden');
                        btnPerfil.setAttribute('aria-expanded', 'false');
                        return;
                    }

                    perfilMenu.removeAttribute('hidden');
                    btnPerfil.setAttribute('aria-expanded', 'true');
                });

                document.addEventListener('click', function (event) {
                    if (!perfilMenuContainer.contains(event.target)) {
                        perfilMenu.setAttribute('hidden', 'hidden');
                        btnPerfil.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        perfilMenu.setAttribute('hidden', 'hidden');
                        btnPerfil.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    </script>
</body>
</html>