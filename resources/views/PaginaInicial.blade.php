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
                <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('pontos-coleta.index') }}'">Meus pontos de coleta</button>
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
                <form class="formPesquisaMedicamento" id="formPesquisaMedicamento" method="GET" action="{{ route('pagina-inicial') }}" autocomplete="off">
                    <div class="pesquisaAutocomplete">
                        <input
                            type="text"
                            name="medicamento"
                            id="inputPesquisaMedicamento"
                            value="{{ $termoMedicamento }}"
                            placeholder="Pesquisar Medicamento"
                            aria-label="Pesquisar medicamento"
                            aria-controls="sugestoesMedicamentos"
                            aria-expanded="false"
                            data-sugestoes-url="{{ route('medicamentos.sugestoes') }}"
                            autocomplete="off"
                        >
                        <div class="sugestoesMedicamentos" id="sugestoesMedicamentos" role="listbox" hidden></div>
                    </div>
                    <button class="roxo" type="submit">Pesquisar</button>
                </form>
                <button class="roxo" onclick="window.location.href='{{ route('cadastro-medicamento') }}'">Doar Medicamento</button>
            </div>
            <div class="navDireita">
                <div class="estoqueMenuContainer" id="estoqueMenuContainer">
                    <button type="button" class="{{ $temAcessoFarmaceutico && $entidadesGerenciaveis->isNotEmpty() ? 'roxo' : 'cinza' }}" id="btnEstoque" aria-expanded="false" aria-controls="estoqueMenu" @disabled(!$temAcessoFarmaceutico || $entidadesGerenciaveis->isEmpty())>Gerenciar Remédios</button>
                    <div class="estoqueMenu card" id="estoqueMenu" hidden>
                        @forelse ($entidadesGerenciaveis as $entidadeGerenciavel)
                            <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('estoque-medicamento', $entidadeGerenciavel->id) }}'">
                                {{ $entidadeGerenciavel->nome_fantasia ?: $entidadeGerenciavel->razao_social }}
                            </button>
                        @empty
                            <button type="button" class="perfilMenuItem" disabled>Nenhum ponto vinculado</button>
                        @endforelse
                    </div>
                </div>
                <div class="estoqueMenuContainer" id="validacaoMenuContainer">
                    <button type="button" class="{{ $temAcessoFarmaceutico && $entidadesGerenciaveis->isNotEmpty() ? 'roxo' : 'cinza' }}" id="btnValidacao" aria-expanded="false" aria-controls="validacaoMenu" @disabled(!$temAcessoFarmaceutico || $entidadesGerenciaveis->isEmpty())>Validar Doação</button>
                    <div class="estoqueMenu card" id="validacaoMenu" hidden>
                        @forelse ($entidadesGerenciaveis as $entidadeGerenciavel)
                            <button type="button" class="perfilMenuItem" onclick="window.location.href='{{ route('validar-doacao', $entidadeGerenciavel->id) }}'">
                                {{ $entidadeGerenciavel->nome_fantasia ?: $entidadeGerenciavel->razao_social }}
                            </button>
                        @empty
                            <button type="button" class="perfilMenuItem" disabled>Nenhum ponto vinculado</button>
                        @endforelse
                    </div>
                </div>
            </div>
    </nav>

    <main class="dashboard">
        <section class="Mapa card">
            <x-mapa-ponto-coleta :mostrar-busca="false" :pontos="$pontosColeta" />
        </section>

        <aside class="listaPontos card">
            <h2>{{ $termoMedicamento !== '' ? 'Pontos com medicamento' : 'Pontos de Coleta Próximos' }}</h2>
            @if ($termoMedicamento !== '')
                <p class="resultadoBusca">Resultado para "{{ $termoMedicamento }}"</p>
            @endif
            <div class="listaPontosItens">

                @forelse ($pontosColeta as $pontoColeta)
                    @php
                        $nomePonto = $pontoColeta->nome_fantasia ?: $pontoColeta->razao_social;
                        $enderecoPonto = collect([
                            $pontoColeta->logradouro,
                            $pontoColeta->numero,
                            $pontoColeta->bairro,
                            $pontoColeta->municipio,
                            $pontoColeta->estado,
                        ])->filter()->implode(', ');
                    @endphp
                    <div class="PontoColeta {{ $loop->first ? 'ativo' : '' }}" data-latitude="{{ $pontoColeta->latitude }}" data-longitude="{{ $pontoColeta->longitude }}">
                        <div class="pontoInfo">
                            <p>{{ $nomePonto }}</p>
                            <small>{{ $enderecoPonto ?: 'Endereco nao informado' }}</small>
                            <small>{{ $pontoColeta->horario_funcionamento ?: 'Horario nao informado' }}</small>
                            @if ($termoMedicamento !== '' && isset($pontoColeta->medicamento_encontrado))
                                <small class="medicamentoDisponivel">
                                    {{ $pontoColeta->medicamento_encontrado }} - {{ $pontoColeta->quantidade_disponivel }} disponivel(is)
                                </small>
                            @endif
                            <a class="abrirMaps" href="https://www.google.com/maps/dir/?api=1&destination={{ $pontoColeta->latitude }},{{ $pontoColeta->longitude }}&travelmode=driving" target="_blank" rel="noopener noreferrer">Abrir no Google Maps</a>
                        </div>
                        <span class="distanciaPonto">Calculando</span>
                    </div>
                @empty
                    <div class="PontoColeta vazio">
                        <div class="pontoInfo">
                            <p>{{ $termoMedicamento !== '' ? 'Nenhum ponto encontrado' : 'Nenhum ponto aprovado' }}</p>
                            <small>{{ $termoMedicamento !== '' ? 'Tente pesquisar outro medicamento ou conferir se ele ja foi validado no estoque.' : 'Cadastre um ponto de coleta para aparecer aqui.' }}</small>
                        </div>
                    </div>
                @endforelse
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
            const btnEstoque = document.getElementById('btnEstoque');
            const estoqueMenu = document.getElementById('estoqueMenu');
            const estoqueMenuContainer = document.getElementById('estoqueMenuContainer');
            const btnValidacao = document.getElementById('btnValidacao');
            const validacaoMenu = document.getElementById('validacaoMenu');
            const validacaoMenuContainer = document.getElementById('validacaoMenuContainer');
            const listaPontosItens = document.querySelector('.listaPontosItens');
            const formPesquisaMedicamento = document.getElementById('formPesquisaMedicamento');
            const inputPesquisaMedicamento = document.getElementById('inputPesquisaMedicamento');
            const sugestoesMedicamentos = document.getElementById('sugestoesMedicamentos');
            let timeoutSugestoes = null;
            let requisicaoSugestoes = null;
            let ultimaLocalizacaoUsuario = null;

            const esconderSugestoes = function () {
                if (!sugestoesMedicamentos || !inputPesquisaMedicamento) return;

                sugestoesMedicamentos.innerHTML = '';
                sugestoesMedicamentos.setAttribute('hidden', 'hidden');
                inputPesquisaMedicamento.setAttribute('aria-expanded', 'false');
            };

            const enviarPesquisaMedicamento = function (nomeMedicamento) {
                if (!formPesquisaMedicamento || !inputPesquisaMedicamento) return;

                inputPesquisaMedicamento.value = nomeMedicamento;
                esconderSugestoes();
                formPesquisaMedicamento.submit();
            };

            const renderizarSugestoes = function (sugestoes) {
                if (!sugestoesMedicamentos || !inputPesquisaMedicamento) return;

                sugestoesMedicamentos.innerHTML = '';

                if (!Array.isArray(sugestoes) || sugestoes.length === 0) {
                    esconderSugestoes();
                    return;
                }

                sugestoes.forEach(function (nomeMedicamento) {
                    const botao = document.createElement('button');
                    botao.type = 'button';
                    botao.className = 'sugestaoMedicamento';
                    botao.setAttribute('role', 'option');
                    botao.textContent = nomeMedicamento;
                    botao.addEventListener('click', function () {
                        enviarPesquisaMedicamento(nomeMedicamento);
                    });

                    sugestoesMedicamentos.appendChild(botao);
                });

                sugestoesMedicamentos.removeAttribute('hidden');
                inputPesquisaMedicamento.setAttribute('aria-expanded', 'true');
            };

            const buscarSugestoesMedicamentos = function () {
                if (!inputPesquisaMedicamento || !sugestoesMedicamentos) return;

                const termo = inputPesquisaMedicamento.value.trim();

                if (termo.length < 2) {
                    esconderSugestoes();
                    return;
                }

                if (requisicaoSugestoes) {
                    requisicaoSugestoes.abort();
                }

                requisicaoSugestoes = new AbortController();

                const url = new URL(inputPesquisaMedicamento.dataset.sugestoesUrl, window.location.origin);
                url.searchParams.set('termo', termo);

                fetch(url.toString(), {
                    headers: {
                        Accept: 'application/json'
                    },
                    signal: requisicaoSugestoes.signal
                })
                    .then(function (response) {
                        if (!response.ok) throw new Error('Erro ao buscar sugestoes.');
                        return response.json();
                    })
                    .then(function (sugestoes) {
                        if (inputPesquisaMedicamento.value.trim() !== termo) return;
                        renderizarSugestoes(sugestoes);
                    })
                    .catch(function (error) {
                        if (error.name === 'AbortError') return;
                        esconderSugestoes();
                    });
            };

            if (inputPesquisaMedicamento) {
                inputPesquisaMedicamento.addEventListener('input', function () {
                    window.clearTimeout(timeoutSugestoes);
                    timeoutSugestoes = window.setTimeout(buscarSugestoesMedicamentos, 300);
                });

                inputPesquisaMedicamento.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        esconderSugestoes();
                    }
                });
            }

            document.addEventListener('click', function (event) {
                if (!sugestoesMedicamentos || !inputPesquisaMedicamento) return;

                const clicouNaBusca = inputPesquisaMedicamento.contains(event.target)
                    || sugestoesMedicamentos.contains(event.target);

                if (!clicouNaBusca) {
                    esconderSugestoes();
                }
            });

            const obterDestinoDoCard = function (card) {
                if (!card) return null;

                const latitude = parseFloat(card.dataset.latitude);
                const longitude = parseFloat(card.dataset.longitude);

                if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                    return null;
                }

                return {
                    latitude: latitude,
                    longitude: longitude
                };
            };

            const solicitarRotaParaCard = function (card) {
                const destino = obterDestinoDoCard(card);

                if (!destino) return;

                const distanciaEl = card.querySelector('.distanciaPonto');
                if (distanciaEl) distanciaEl.textContent = 'Rota...';

                window.dispatchEvent(new CustomEvent('sgdm:ponto-selecionado', {
                    detail: destino
                }));
            };

            const atualizarLinksGoogleMaps = function () {
                const links = document.querySelectorAll('.abrirMaps');

                links.forEach(function (link) {
                    const card = link.closest('.PontoColeta');
                    const destino = obterDestinoDoCard(card);

                    if (!destino) return;

                    const url = new URL('https://www.google.com/maps/dir/');
                    url.searchParams.set('api', '1');
                    url.searchParams.set('destination', destino.latitude + ',' + destino.longitude);
                    url.searchParams.set('travelmode', 'driving');

                    if (ultimaLocalizacaoUsuario) {
                        url.searchParams.set('origin', ultimaLocalizacaoUsuario.lat + ',' + ultimaLocalizacaoUsuario.lng);
                    }

                    link.href = url.toString();
                });
            };

            const calcularDistanciaKm = function (origem, destino) {
                const raioTerraKm = 6371;
                const grausParaRadianos = function (valor) {
                    return valor * Math.PI / 180;
                };
                const diferencaLatitude = grausParaRadianos(destino.lat - origem.lat);
                const diferencaLongitude = grausParaRadianos(destino.lng - origem.lng);
                const latOrigem = grausParaRadianos(origem.lat);
                const latDestino = grausParaRadianos(destino.lat);
                const a = Math.sin(diferencaLatitude / 2) * Math.sin(diferencaLatitude / 2)
                    + Math.cos(latOrigem) * Math.cos(latDestino)
                    * Math.sin(diferencaLongitude / 2) * Math.sin(diferencaLongitude / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return raioTerraKm * c;
            };

            const formatarDistancia = function (distanciaKm) {
                if (distanciaKm < 1) {
                    return Math.round(distanciaKm * 1000) + ' m';
                }

                return distanciaKm.toFixed(1).replace('.', ',') + ' km';
            };

            const ordenarPontosPorDistancia = function (localizacaoUsuario) {
                if (!listaPontosItens) return;

                const cards = Array.from(listaPontosItens.querySelectorAll('.PontoColeta:not(.vazio)'));

                cards.forEach(function (card) {
                    const latitude = parseFloat(card.dataset.latitude);
                    const longitude = parseFloat(card.dataset.longitude);
                    const distanciaEl = card.querySelector('.distanciaPonto');

                    if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                        card.dataset.distancia = String(Number.MAX_SAFE_INTEGER);
                        if (distanciaEl) distanciaEl.textContent = 'Sem local';
                        return;
                    }

                    const distanciaKm = calcularDistanciaKm(localizacaoUsuario, {
                        lat: latitude,
                        lng: longitude
                    });

                    card.dataset.distancia = String(distanciaKm);
                    if (distanciaEl) distanciaEl.textContent = formatarDistancia(distanciaKm);
                });

                cards.sort(function (a, b) {
                    return parseFloat(a.dataset.distancia) - parseFloat(b.dataset.distancia);
                });

                cards.forEach(function (card, index) {
                    card.classList.toggle('ativo', index === 0);
                    listaPontosItens.appendChild(card);
                });

                if (cards[0]) {
                    solicitarRotaParaCard(cards[0]);
                }
            };

            window.addEventListener('sgdm:route-info', function (event) {
                const cards = Array.from(document.querySelectorAll('.listaPontosItens .PontoColeta:not(.vazio)'));
                const card = cards.find(function (item) {
                    return Number(item.dataset.latitude) === Number(event.detail.latitude)
                        && Number(item.dataset.longitude) === Number(event.detail.longitude);
                });

                if (!card) return;

                const distanciaEl = card.querySelector('.distanciaPonto');
                if (distanciaEl) {
                    distanciaEl.innerHTML = event.detail.distance + '<small>' + event.detail.duration + '</small>';
                }
            });

            window.addEventListener('sgdm:route-error', function (event) {
                const cards = Array.from(document.querySelectorAll('.listaPontosItens .PontoColeta:not(.vazio)'));
                const card = cards.find(function (item) {
                    return Number(item.dataset.latitude) === Number(event.detail.latitude)
                        && Number(item.dataset.longitude) === Number(event.detail.longitude);
                });

                if (!card) return;

                const distanciaEl = card.querySelector('.distanciaPonto');
                if (distanciaEl) distanciaEl.textContent = 'Sem rota';
            });

            window.addEventListener('sgdm:user-location', function (event) {
                ultimaLocalizacaoUsuario = event.detail;
                atualizarLinksGoogleMaps();
                ordenarPontosPorDistancia(event.detail);
            });

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    ultimaLocalizacaoUsuario = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    atualizarLinksGoogleMaps();
                    ordenarPontosPorDistancia(ultimaLocalizacaoUsuario);
                }, function () {}, {
                    enableHighAccuracy: true,
                    timeout: 8000,
                    maximumAge: 60000
                });
            }

            pontos.forEach(function (ponto) {
                ponto.addEventListener('click', function () {
                    pontos.forEach(function (item) {
                        item.classList.remove('ativo');
                    });

                    ponto.classList.add('ativo');
                    solicitarRotaParaCard(ponto);
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

            if (btnEstoque && estoqueMenu && estoqueMenuContainer) {
                btnEstoque.addEventListener('click', function () {
                    const isOpen = !estoqueMenu.hasAttribute('hidden');

                    if (isOpen) {
                        estoqueMenu.setAttribute('hidden', 'hidden');
                        btnEstoque.setAttribute('aria-expanded', 'false');
                        return;
                    }

                    estoqueMenu.removeAttribute('hidden');
                    btnEstoque.setAttribute('aria-expanded', 'true');
                });

                document.addEventListener('click', function (event) {
                    if (!estoqueMenuContainer.contains(event.target)) {
                        estoqueMenu.setAttribute('hidden', 'hidden');
                        btnEstoque.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        estoqueMenu.setAttribute('hidden', 'hidden');
                        btnEstoque.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            if (btnValidacao && validacaoMenu && validacaoMenuContainer) {
                btnValidacao.addEventListener('click', function () {
                    const isOpen = !validacaoMenu.hasAttribute('hidden');

                    if (isOpen) {
                        validacaoMenu.setAttribute('hidden', 'hidden');
                        btnValidacao.setAttribute('aria-expanded', 'false');
                        return;
                    }

                    validacaoMenu.removeAttribute('hidden');
                    btnValidacao.setAttribute('aria-expanded', 'true');
                });

                document.addEventListener('click', function (event) {
                    if (!validacaoMenuContainer.contains(event.target)) {
                        validacaoMenu.setAttribute('hidden', 'hidden');
                        btnValidacao.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        validacaoMenu.setAttribute('hidden', 'hidden');
                        btnValidacao.setAttribute('aria-expanded', 'false');
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
