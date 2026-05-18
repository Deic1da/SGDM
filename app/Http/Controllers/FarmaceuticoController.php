<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFarmaceuticoRequest;
use App\Models\Entidade;
use App\Models\Farmaceutico;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class FarmaceuticoController extends Controller
{
    public function store(StoreFarmaceuticoRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        $farmaceutico = Farmaceutico::create([
            'id_usuario_pf' => auth()->id(),
            'num_crf' => $dados['num_crf'],
            'uf_crf' => $dados['uf_crf'],
            'status_profissional' => 'Ativo',
        ]);

        Entidade::where('id_dono_entidade', auth()->id())
            ->where('status', '!=', 'Inativo')
            ->get()
            ->each(function (Entidade $entidade) use ($farmaceutico): void {
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

                if (!$entidade->farmaceutico_rt) {
                    $entidade->update(['farmaceutico_rt' => $farmaceutico->id]);
                }
            });

        return redirect()
            ->route('pagina-inicial')
            ->with('success', 'Cadastro de farmaceutico realizado com sucesso.');
    }
}
