<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntidadeRequest;
use App\Http\Requests\UpdateEntidadeRequest;
use App\Models\Entidade;
use App\Models\Farmaceutico;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EntidadeController extends Controller
{
    public function index(): View
    {
        $entidades = Entidade::where('id_dono_entidade', auth()->id())
            ->orderByDesc('id')
            ->get();

        return view('PontosColeta.index', ['entidades' => $entidades]);
    }

    public function store(StoreEntidadeRequest $request)
    {
        $dados = $request->validated();

        $entidade = Entidade::create([
            'razao_social' => $dados['razao_social'],
            'nome_fantasia' => $dados['nome_fantasia'] ?? null,
            'cnpj' => $dados['cnpj'],
            'id_dono_entidade' => auth()->id(),
            'horario_funcionamento' => $dados['horario_funcionamento'],
            'aceita_validade_curta' => (bool) ($dados['aceita_validade_curta'] ?? false),
            'status' => 'Aprovado',
            'latitude' => (float) ($dados['latitude'] ?? 0),
            'longitude' => (float) ($dados['longitude'] ?? 0),
            'farmaceutico_rt' => null,
            'cep' => $dados['cep'],
            'logradouro' => $dados['logradouro'],
            'numero' => $dados['numero'],
            'bairro' => $dados['bairro'],
            'municipio' => $dados['municipio'],
            'estado' => $dados['estado'],
        ]);

        $farmaceutico = Farmaceutico::where('id_usuario_pf', auth()->id())
            ->where('status_profissional', 'Ativo')
            ->first();

        if ($farmaceutico) {
            $this->vincularResponsavelTecnico($entidade, $farmaceutico);
        }

        return redirect()
            ->route('cadastro-ponto-coleta')
            ->with('success', 'Ponto de coleta cadastrado com status Aprovado.');
    }

    public function edit(Entidade $entidade): View
    {
        $this->garantirDono($entidade);

        return view('PontosColeta.edit', ['entidade' => $entidade]);
    }

    public function update(UpdateEntidadeRequest $request, Entidade $entidade): RedirectResponse
    {
        $this->garantirDono($entidade);

        $dados = $request->validated();

        $entidade->update([
            'razao_social' => $dados['razao_social'],
            'nome_fantasia' => $dados['nome_fantasia'] ?? null,
            'cnpj' => $dados['cnpj'],
            'horario_funcionamento' => $dados['horario_funcionamento'],
            'aceita_validade_curta' => (bool) ($dados['aceita_validade_curta'] ?? false),
            'latitude' => (float) ($dados['latitude'] ?? $entidade->latitude),
            'longitude' => (float) ($dados['longitude'] ?? $entidade->longitude),
            'cep' => $dados['cep'],
            'logradouro' => $dados['logradouro'],
            'numero' => $dados['numero'],
            'bairro' => $dados['bairro'],
            'municipio' => $dados['municipio'],
            'estado' => $dados['estado'],
        ]);

        return redirect()
            ->route('pontos-coleta.index')
            ->with('success', 'Ponto de coleta atualizado com sucesso.');
    }

    public function destroy(Entidade $entidade): RedirectResponse
    {
        $this->garantirDono($entidade);

        $entidade->update(['status' => 'Inativo']);

        return redirect()
            ->route('pontos-coleta.index')
            ->with('success', 'Ponto de coleta desativado com sucesso.');
    }

    public function reativar(Entidade $entidade): RedirectResponse
    {
        $this->garantirDono($entidade);

        $entidade->update(['status' => 'Aprovado']);

        return redirect()
            ->route('pontos-coleta.index')
            ->with('success', 'Ponto de coleta reativado com sucesso.');
    }

    private function garantirDono(Entidade $entidade): void
    {
        abort_unless($entidade->id_dono_entidade === auth()->id(), 403);
    }

    private function vincularResponsavelTecnico(Entidade $entidade, Farmaceutico $farmaceutico): void
    {
        DB::table('vinculos_farmaceutico')->updateOrInsert(
            [
                'id_entidade' => $entidade->id,
                'id_farmaceutico' => $farmaceutico->id,
            ],
            [
                'tipo_vinculo' => 'Responsavel_Tecnico',
                'status_vinculo' => 'Aceito',
                'date_inicio' => Carbon::today()->toDateString(),
                'data_fim' => null,
            ]
        );

        $entidade->update(['farmaceutico_rt' => $farmaceutico->id]);
    }
}
