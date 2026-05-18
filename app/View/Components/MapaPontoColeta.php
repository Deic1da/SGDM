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

    public function __construct(string|float|null $latitude = null, string|float|null $longitude = null, bool $mostrarBusca = true)
    {
        $this->apiKey = config('services.google_maps.key');
        $this->latitude = (string) ($latitude ?? '-23.550520');
        $this->longitude = (string) ($longitude ?? '-46.633308');
        $this->mostrarBusca = $mostrarBusca;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mapa-ponto-coleta');
    }
}
