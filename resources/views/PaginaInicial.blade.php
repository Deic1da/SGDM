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
    @php
        $fotoPerfil = auth()->user()?->foto_perfil;
        $urlFotoPerfil = null;

        if (!empty($fotoPerfil)) {
            $urlFotoPerfil = str_starts_with($fotoPerfil, 'http')
                ? $fotoPerfil
                : asset('storage/' . $fotoPerfil);
        }
    @endphp

    @if (session('success'))
    <div class="flash sucesso">{{ session('success') }}</div>
    @endif

    @if ($errors->has('foto_perfil'))
    <div class="flash erro">{{ $errors->first('foto_perfil') }}</div>
    @endif

    <header class="topbar">
        <div class="brandBlock">
            <h1 class="Logo">SGDM</h1>
        </div>
        <div class="topActions" id="perfilMenuContainer">
            <button class="btnConfig is-hidden" aria-hidden="true" tabindex="-1">&#9881;</button>
            <button class="btnPerfil" id="btnPerfil" aria-expanded="false" aria-controls="perfilMenu">
                @if ($urlFotoPerfil)
                    <img class="perfilAvatar" src="{{ $urlFotoPerfil }}" alt="Foto do perfil">
                @else
                    &#128100;
                @endif
            </button>
            <div class="perfilMenu card" id="perfilMenu" hidden>
                <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('cadastro-farmaceutico') }}'">Cadastro de Farmacêutico</button>
                <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('cadastro-ponto-coleta') }}'">Cadastrar ponto de coleta</button>
                <form method="POST" action="{{ route('perfil-foto.update') }}" enctype="multipart/form-data" id="formFotoPerfil">
                    @csrf
                    <input type="file" name="foto_perfil" id="inputFotoPerfil" accept="image/*" hidden>
                    <button type="button" class="perfilMenuItem" id="btnTrocarFoto" aria-label="Trocar foto do perfil">Trocar foto do perfil</button>
                </form>
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
            const btnTrocarFoto = document.getElementById('btnTrocarFoto');
            const inputFotoPerfil = document.getElementById('inputFotoPerfil');
            const formFotoPerfil = document.getElementById('formFotoPerfil');

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

            if (btnTrocarFoto && inputFotoPerfil && formFotoPerfil) {
                btnTrocarFoto.addEventListener('click', function () {
                    inputFotoPerfil.click();
                });

                inputFotoPerfil.addEventListener('change', function () {
                    if (inputFotoPerfil.files && inputFotoPerfil.files.length > 0) {
                        formFotoPerfil.submit();
                    }
                });
            }
        });
    </script>
</body>
</html>