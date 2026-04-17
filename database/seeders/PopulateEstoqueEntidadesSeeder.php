<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateEstoqueEntidadesSeeder extends Seeder
{
    public function run(): void
    {
        $entidades = DB::table('entidades')->select('id', 'farmaceutico_rt')->get();
        $doadorId = DB::table('usuarios')->value('id');
        $farmaceuticoFallback = DB::table('farmaceuticos')->value('id');

        if (!$doadorId || !$farmaceuticoFallback || $entidades->isEmpty()) {
            $this->command?->warn('Nao foi possivel popular estoque: faltam usuarios, farmaceuticos ou entidades.');
            return;
        }

        $catalogo = [
            ['nome' => 'Dipirona 500mg', 'forma' => 'Comprimido', 'condicao' => 'Cartela lacrada', 'validade' => '2027-03-10', 'qtd' => 120, 'status_estoque' => 'Disponivel'],
            ['nome' => 'Paracetamol 750mg', 'forma' => 'Comprimido', 'condicao' => 'Cartela lacrada', 'validade' => '2026-12-20', 'qtd' => 80, 'status_estoque' => 'Reservado'],
            ['nome' => 'Xarope Ambroxol', 'forma' => 'Xarope', 'condicao' => 'Caixa lacrada', 'validade' => '2026-10-15', 'qtd' => 35, 'status_estoque' => 'Disponivel'],
            ['nome' => 'Amoxicilina 500mg', 'forma' => 'Capsula', 'condicao' => 'Blister lacrado', 'validade' => '2027-01-25', 'qtd' => 60, 'status_estoque' => 'Disponivel'],
            ['nome' => 'Ibuprofeno 600mg', 'forma' => 'Comprimido', 'condicao' => 'Cartela lacrada', 'validade' => '2026-11-30', 'qtd' => 45, 'status_estoque' => 'Entregue'],
        ];

        DB::transaction(function () use ($entidades, $catalogo, $doadorId, $farmaceuticoFallback) {
            foreach ($entidades as $entidade) {
                $estoqueAtual = DB::table('estoque_entidade')->where('id_entidade', $entidade->id)->count();

                if ($estoqueAtual >= 3) {
                    continue;
                }

                $farmaceuticoId = DB::table('vinculos_farmaceutico')
                    ->where('id_entidade', $entidade->id)
                    ->where('status_vinculo', 'Aceito')
                    ->value('id_farmaceutico');

                if (!$farmaceuticoId) {
                    $farmaceuticoId = $entidade->farmaceutico_rt ?: $farmaceuticoFallback;
                }

                $faltam = 3 - $estoqueAtual;

                for ($i = 0; $i < $faltam; $i++) {
                    $item = $catalogo[($entidade->id + $i) % count($catalogo)];

                    $medicamentoId = DB::table('medicamentos_doacao')->insertGetId([
                        'id_doador' => $doadorId,
                        'nome_medicamento' => $item['nome'],
                        'forma_farmaceutica' => $item['forma'],
                        'condicao_embalagem' => $item['condicao'],
                        'data_validade' => $item['validade'],
                        'status_doacao' => 'Aprovado',
                        'id_entidade_destino' => $entidade->id,
                    ]);

                    $validacaoId = DB::table('validacoes')->insertGetId([
                        'id_medicamento_doacao' => $medicamentoId,
                        'id_farmaceutico_validante' => $farmaceuticoId,
                        'status_validacao' => 'Aprovado',
                        'motivo_rejeicao' => null,
                    ]);

                    DB::table('estoque_entidade')->insert([
                        'id_entidade' => $entidade->id,
                        'id_validacao' => $validacaoId,
                        'quantidade' => $item['qtd'],
                        'status_estoque' => $item['status_estoque'],
                    ]);
                }
            }
        });

        $this->command?->info('Estoque de entidades populado com sucesso.');
    }
}
