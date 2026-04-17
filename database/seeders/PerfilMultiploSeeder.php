<?php

namespace Database\Seeders;

use App\Models\Entidade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerfilMultiploSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $email = 'perfil.multiplo@sgdm.local';
            $cpf = '93541134780';

            $user = User::firstOrNew(['email' => $email]);
            $user->nome_completo = 'Perfil Multiplo SGDM';
            $user->cpf = $cpf;
            $user->telefone = '11999998888';
            $user->password = Hash::make('Senha@1234');
            $user->cep = '01001000';
            $user->logradouro = 'Praca da Se';
            $user->numero = '100';
            $user->bairro = 'Se';
            $user->municipio = 'Sao Paulo';
            $user->estado = 'SP';
            $user->save();

            $farmaceutico = DB::table('farmaceuticos')->where('id_usuario_pf', $user->id)->first();

            if (!$farmaceutico) {
                do {
                    $crf = 'CRFSP' . random_int(10000, 99999);
                } while (DB::table('farmaceuticos')->where('num_crf', $crf)->exists());

                $farmaceuticoId = DB::table('farmaceuticos')->insertGetId([
                    'id_usuario_pf' => $user->id,
                    'num_crf' => $crf,
                    'uf_crf' => 'SP',
                    'status_profissional' => 'Ativo',
                ]);
            } else {
                $farmaceuticoId = $farmaceutico->id;
                DB::table('farmaceuticos')
                    ->where('id', $farmaceuticoId)
                    ->update([
                        'uf_crf' => 'SP',
                        'status_profissional' => 'Ativo',
                    ]);
            }

            $entidade = Entidade::firstOrNew([
                'id_dono_entidade' => $user->id,
                'razao_social' => 'Ponto Integrado SGDM',
            ]);

            if (!$entidade->exists) {
                do {
                    $cnpj = str_pad((string) random_int(10000000000000, 99999999999999), 14, '0', STR_PAD_LEFT);
                } while (DB::table('entidades')->where('cnpj', $cnpj)->exists());

                $entidade->cnpj = $cnpj;
            }

            $entidade->nome_fantasia = 'Ponto Integrado SGDM';
            $entidade->horario_funcionamento = '08:00 as 18:00';
            $entidade->aceita_validade_curta = true;
            $entidade->status = 'Aprovado';
            $entidade->latitude = -23.55052;
            $entidade->longitude = -46.633308;
            $entidade->farmaceutico_rt = $farmaceuticoId;
            $entidade->cep = '01001000';
            $entidade->logradouro = 'Praca da Se';
            $entidade->numero = '100';
            $entidade->bairro = 'Se';
            $entidade->municipio = 'Sao Paulo';
            $entidade->estado = 'SP';
            $entidade->save();

            DB::table('vinculos_farmaceutico')->updateOrInsert(
                [
                    'id_entidade' => $entidade->id,
                    'id_farmaceutico' => $farmaceuticoId,
                ],
                [
                    'tipo_vinculo' => 'Responsavel_Tecnico',
                    'status_vinculo' => 'Aceito',
                    'date_inicio' => Carbon::today()->toDateString(),
                    'data_fim' => null,
                ]
            );
        });
    }
}
