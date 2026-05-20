<div>
    <label></label>
    @if ($mostrarBusca)
    <div class="mapaBusca">
        <input id="mapa-busca-endereco" type="text" placeholder="Digite endereco, bairro ou cidade" aria-label="Buscar endereco no mapa">
        <button id="mapa-busca-botao" type="button">Buscar</button>
    </div>
    @endif
    @if ($apiKey)
    <div id="mapa-ponto-coleta" style="border-radius: 12px; border: 1px solid #d9d9d9; width: 100%;height: 100%;"></div>
    @else
    <div style="border-radius: 12px; border: 1px solid #d9d9d9; width: 100%; height: 100%; min-height: 240px; display: grid; place-items: center; padding: 16px; text-align: center; color: #555; background: #f8f8f8;">
        Configure GOOGLE_MAPS_API_KEY no arquivo .env para carregar o mapa.
    </div>
    @endif
    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $latitude) }}">
    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $longitude) }}">
</div>
@if ($apiKey)
@once

    <script>
        (function() {
            const pontosMapa = @json($pontosMapa);
            const permiteEditar = @json($mostrarBusca);
            const roxoSistema = '#773dbe';

            function atualizarCampos(latLng) {
                document.getElementById('latitude').value = latLng.lat().toFixed(6);
                document.getElementById('longitude').value = latLng.lng().toFixed(6);
            }

            function criarIconePonto(selecionado) {
                const largura = selecionado ? 42 : 34;
                const altura = selecionado ? 56 : 46;
                const svg = selecionado
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="42" height="56" viewBox="0 0 42 56"><path fill="' + roxoSistema + '" stroke="#ffffff" stroke-width="4" d="M21 2C10.51 2 2 10.51 2 21c0 14.25 19 32 19 32s19-17.75 19-32C40 10.51 31.49 2 21 2z"/><circle cx="21" cy="21" r="8" fill="#ffffff"/><circle cx="21" cy="21" r="4" fill="' + roxoSistema + '"/></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="34" height="46" viewBox="0 0 34 46"><path fill="' + roxoSistema + '" d="M17 0C7.61 0 0 7.61 0 17c0 12.75 17 29 17 29s17-16.25 17-29C34 7.61 26.39 0 17 0z"/><circle cx="17" cy="17" r="7" fill="#ffffff"/></svg>';

                return {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
                    scaledSize: new google.maps.Size(largura, altura),
                    anchor: new google.maps.Point(largura / 2, altura - 2)
                };
            }

            function criarIconeUsuario(heading) {
                return {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                    fillColor: roxoSistema,
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2,
                    scale: 7,
                    rotation: Number.isFinite(heading) ? heading : 0,
                    anchor: new google.maps.Point(0, 2)
                };
            }

            function chaveCoordenada(latitude, longitude) {
                return Number(latitude).toFixed(6) + ',' + Number(longitude).toFixed(6);
            }

            window.initMapaPontoColeta = function() {
                const elMapa = document.getElementById('mapa-ponto-coleta');
                if (!elMapa) return;
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const buscaInput = document.getElementById('mapa-busca-endereco');
                const buscaBotao = document.getElementById('mapa-busca-botao');
                const latInicial = parseFloat(latInput.value || '-23.550520');
                const lngInicial = parseFloat(lngInput.value || '-46.633308');
                const primeiroPonto = pontosMapa.length > 0 ? pontosMapa[0] : null;
                const centro = primeiroPonto ? {
                    lat: parseFloat(primeiroPonto.latitude),
                    lng: parseFloat(primeiroPonto.longitude)
                } : {
                    lat: latInicial,
                    lng: lngInicial
                };
                const mapa = new google.maps.Map(elMapa, {
                    center: centro,
                    zoom: 13,
                    mapTypeControl: false,
                    streetViewControl: false,
                    styles: [
                        {
                            featureType: 'poi',
                            stylers: [{ visibility: 'off' }]
                        },
                        {
                            featureType: 'transit.station',
                            stylers: [{ visibility: 'off' }]
                        }
                    ]
                });
                const geocoder = new google.maps.Geocoder();
                const infoWindow = new google.maps.InfoWindow();
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map: mapa,
                    suppressMarkers: true,
                    preserveViewport: false,
                    polylineOptions: {
                        strokeColor: roxoSistema,
                        strokeOpacity: 0.9,
                        strokeWeight: 5
                    }
                });
                let marcador = null;
                let marcadorUsuario = null;
                let ultimaLocalizacaoUsuario = null;
                let marcadorSelecionado = null;
                const marcadoresPontos = new Map();
                const deveCentralizarUsuario = !permiteEditar;
                const iconePonto = criarIconePonto(false);
                const iconePontoSelecionado = criarIconePonto(true);

                const destacarPonto = function (destino) {
                    if (!destino) return;

                    if (marcadorSelecionado) {
                        marcadorSelecionado.setIcon(iconePonto);
                        marcadorSelecionado.setZIndex(null);
                        marcadorSelecionado.setAnimation(null);
                    }

                    const chave = chaveCoordenada(destino.latitude, destino.longitude);
                    const marcadorDestino = marcadoresPontos.get(chave);

                    if (!marcadorDestino) return;

                    marcadorDestino.setIcon(iconePontoSelecionado);
                    marcadorDestino.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
                    marcadorDestino.setAnimation(google.maps.Animation.BOUNCE);
                    mapa.panTo(marcadorDestino.getPosition());
                    marcadorSelecionado = marcadorDestino;

                    window.setTimeout(function () {
                        marcadorDestino.setAnimation(null);
                    }, 900);
                };

                const desenharRota = function (destino) {
                    if (!ultimaLocalizacaoUsuario || !destino) return;

                    directionsService.route({
                        origin: ultimaLocalizacaoUsuario,
                        destination: {
                            lat: parseFloat(destino.latitude),
                            lng: parseFloat(destino.longitude)
                        },
                        travelMode: google.maps.TravelMode.DRIVING
                    }, function (resultado, status) {
                        if (status !== 'OK' || !resultado.routes[0] || !resultado.routes[0].legs[0]) {
                            window.dispatchEvent(new CustomEvent('sgdm:route-error', {
                                detail: destino
                            }));
                            return;
                        }

                        const trecho = resultado.routes[0].legs[0];
                        directionsRenderer.setDirections(resultado);
                        window.dispatchEvent(new CustomEvent('sgdm:route-info', {
                            detail: {
                                latitude: destino.latitude,
                                longitude: destino.longitude,
                                distance: trecho.distance ? trecho.distance.text : '',
                                duration: trecho.duration ? trecho.duration.text : ''
                            }
                        }));
                    });
                };

                if (pontosMapa.length > 0) {
                    const bounds = new google.maps.LatLngBounds();

                    pontosMapa.forEach(function (ponto) {
                        const posicao = {
                            lat: parseFloat(ponto.latitude),
                            lng: parseFloat(ponto.longitude)
                        };
                        const marcadorPonto = new google.maps.Marker({
                            position: posicao,
                            map: mapa,
                            title: ponto.nome,
                            icon: iconePonto
                        });

                        marcadoresPontos.set(chaveCoordenada(posicao.lat, posicao.lng), marcadorPonto);

                        marcadorPonto.addListener('click', function () {
                            destacarPonto(ponto);
                            const endereco = ponto.endereco ? '<br><small>' + ponto.endereco + '</small>' : '';
                            infoWindow.setContent('<strong>' + ponto.nome + '</strong>' + endereco);
                            infoWindow.open(mapa, marcadorPonto);
                        });

                        bounds.extend(posicao);
                    });

                    if (!deveCentralizarUsuario && pontosMapa.length > 1) {
                        mapa.fitBounds(bounds);
                    }
                } else if (permiteEditar) {
                    marcador = new google.maps.Marker({
                        position: centro,
                        map: mapa,
                        draggable: true
                    });
                }

                if (deveCentralizarUsuario && navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const localizacaoUsuario = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        const heading = position.coords.heading;
                        ultimaLocalizacaoUsuario = localizacaoUsuario;

                        window.dispatchEvent(new CustomEvent('sgdm:user-location', {
                            detail: localizacaoUsuario
                        }));

                        mapa.setCenter(localizacaoUsuario);
                        mapa.setZoom(14);

                        if (!marcadorUsuario) {
                            marcadorUsuario = new google.maps.Marker({
                                position: localizacaoUsuario,
                                map: mapa,
                                title: 'Sua localizacao',
                                icon: criarIconeUsuario(heading)
                            });
                            return;
                        }

                        marcadorUsuario.setPosition(localizacaoUsuario);
                        marcadorUsuario.setIcon(criarIconeUsuario(heading));
                    }, function () {
                        if (pontosMapa.length > 1) {
                            const bounds = new google.maps.LatLngBounds();
                            pontosMapa.forEach(function (ponto) {
                                bounds.extend({
                                    lat: parseFloat(ponto.latitude),
                                    lng: parseFloat(ponto.longitude)
                                });
                            });
                            mapa.fitBounds(bounds);
                        }
                    }, {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 60000
                    });
                }

                window.addEventListener('sgdm:ponto-selecionado', function (event) {
                    destacarPonto(event.detail);
                    desenharRota(event.detail);
                });

                const buscarEndereco = function () {
                    if (!buscaInput || !buscaInput.value.trim()) return;

                    geocoder.geocode({ address: buscaInput.value.trim() }, function (resultados, status) {
                        if (status !== 'OK' || !resultados[0]) {
                            buscaInput.setCustomValidity('Endereco nao encontrado.');
                            buscaInput.reportValidity();
                            return;
                        }

                        buscaInput.setCustomValidity('');
                        const localizacao = resultados[0].geometry.location;
                        mapa.setCenter(localizacao);
                        mapa.setZoom(15);
                        if (marcador) {
                            marcador.setPosition(localizacao);
                        }
                        atualizarCampos(localizacao);
                    });
                };

                if (marcador) {
                    marcador.addListener('dragend', function() {
                        atualizarCampos(marcador.getPosition());
                    });
                    mapa.addListener('click', function(evento) {
                        marcador.setPosition(evento.latLng);
                        atualizarCampos(evento.latLng);
                    });
                }
                if (buscaBotao) {
                    buscaBotao.addEventListener('click', buscarEndereco);
                }
                if (buscaInput) {
                    buscaInput.addEventListener('keydown', function(evento) {
                        if (evento.key === 'Enter') {
                            evento.preventDefault();
                            buscarEndereco();
                        }
                    });
                }
            };
        })();
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMapaPontoColeta" async defer>
    </script>
@endonce
@endif
