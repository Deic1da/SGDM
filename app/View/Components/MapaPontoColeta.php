<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MapaPontoColeta extends Component
{
    /**
     * Create a new component instance.
     */

    public ?string $apiKey;
    public string $latitude;
    public string $longitude;
    public bool $mostrarBusca;
    public array $pontosMapa;

    public function __construct(string|float|null $latitude = null, string|float|null $longitude = null, bool $mostrarBusca = true, mixed $pontos = [])
    {
        $this->apiKey = config('services.google_maps.key');
        $this->latitude = (string) ($latitude ?? '-23.550520');
        $this->longitude = (string) ($longitude ?? '-46.633308');
        $this->mostrarBusca = $mostrarBusca;
        $this->pontosMapa = collect($pontos)
            ->map(function ($ponto) {
                return [
                    'nome' => $ponto->nome_fantasia ?: $ponto->razao_social,
                    'endereco' => collect([
                        $ponto->logradouro ?? null,
                        $ponto->numero ?? null,
                        $ponto->bairro ?? null,
                        $ponto->municipio ?? null,
                        $ponto->estado ?? null,
                    ])->filter()->implode(', '),
                    'latitude' => $ponto->latitude ?? null,
                    'longitude' => $ponto->longitude ?? null,
                ];
            })
            ->filter(fn ($ponto) => is_numeric($ponto['latitude']) && is_numeric($ponto['longitude']))
            ->values()
            ->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mapa-ponto-coleta');
    }
}
