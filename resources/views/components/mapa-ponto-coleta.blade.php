<div> 
    <label></label>
    <div id="mapa-ponto-coleta" style="border-radius: 12px; border: 1px solid #d9d9d9; width: 100%;height: 100%;"></div>
    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', '-23.550520') }}">
    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', '-46.633308') }}">
</div>
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
                marcador.addListener('dragend', function() {
                    atualizarCampos(marcador.getPosition());
                });
                mapa.addListener('click', function(evento) {
                    marcador.setPosition(evento.latLng);
                    atualizarCampos(evento.latLng);
                });
            };
        })();
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMapaPontoColeta" async defer>
    </script>
@endonce
