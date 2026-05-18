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
            function atualizarCampos(latLng) {
                document.getElementById('latitude').value = latLng.lat().toFixed(6);
                document.getElementById('longitude').value = latLng.lng().toFixed(6);
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
                const centro = {
                    lat: latInicial,
                    lng: lngInicial
                };
                const mapa = new google.maps.Map(elMapa, {
                    center: centro,
                    zoom: 13,
                    mapTypeControl: false,
                    streetViewControl: false
                });
                const marcador = new google.maps.Marker({
                    position: centro,
                    map: mapa,
                    draggable: true
                });
                const geocoder = new google.maps.Geocoder();

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
                        marcador.setPosition(localizacao);
                        atualizarCampos(localizacao);
                    });
                };

                marcador.addListener('dragend', function() {
                    atualizarCampos(marcador.getPosition());
                });
                mapa.addListener('click', function(evento) {
                    marcador.setPosition(evento.latLng);
                    atualizarCampos(evento.latLng);
                });
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
