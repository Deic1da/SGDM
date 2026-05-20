<?php

use App\Models\Entidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function usuarioTeste(array $dados = []): User
{
    static $contador = 1;

    $base = [
        'nome_completo' => 'Usuario Teste '.$contador,
        'cpf' => str_pad((string) $contador, 11, '0', STR_PAD_LEFT),
        'email' => 'usuario'.$contador.'@teste.local',
        'telefone' => '11999999999',
        'password' => Hash::make('Senha@123'),
        'cep' => '01001000',
        'logradouro' => 'Rua Teste',
        'numero' => '100',
        'bairro' => 'Centro',
        'municipio' => 'Sao Paulo',
        'estado' => 'SP',
    ];

    $contador++;

    return User::create(array_merge($base, $dados));
}

function entidadeTeste(User $dono, array $dados = []): Entidade
{
    static $contador = 1;

    $base = [
        'razao_social' => 'Entidade Teste '.$contador,
        'nome_fantasia' => 'Ponto Teste '.$contador,
        'cnpj' => str_pad((string) $contador, 14, '0', STR_PAD_LEFT),
        'id_dono_entidade' => $dono->id,
        'horario_funcionamento' => '08:00 as 18:00',
        'aceita_validade_curta' => true,
        'status' => 'Aprovado',
        'latitude' => -23.550520,
        'longitude' => -46.633308,
        'farmaceutico_rt' => null,
    ];

    $contador++;

    return Entidade::create(array_merge($base, $dados));
}

function farmaceuticoTeste(User $usuario): int
{
    return DB::table('farmaceuticos')->insertGetId([
        'id_usuario_pf' => $usuario->id,
        'num_crf' => 'CRF'.$usuario->id,
        'uf_crf' => 'SP',
        'status_profissional' => 'Ativo',
    ]);
}

test('usuario deslogado nao acessa pagina inicial', function () {
    $this->get(route('pagina-inicial'))
        ->assertRedirect(route('login'));
});

test('usuario sem cadastro farmaceutico nao acessa estoque', function () {
    $usuario = usuarioTeste();
    $entidade = entidadeTeste($usuario);

    $this->actingAs($usuario)
        ->get(route('estoque-medicamento', $entidade))
        ->assertForbidden();
});

test('farmaceutico sem vinculo aceito nao acessa estoque de outra entidade', function () {
    $farmaceuticoUsuario = usuarioTeste();
    $donoEntidade = usuarioTeste();
    $entidade = entidadeTeste($donoEntidade);

    farmaceuticoTeste($farmaceuticoUsuario);

    $this->actingAs($farmaceuticoUsuario)
        ->get(route('estoque-medicamento', $entidade))
        ->assertForbidden();
});

test('farmaceutico vinculado acessa estoque da entidade', function () {
    $farmaceuticoUsuario = usuarioTeste();
    $entidade = entidadeTeste($farmaceuticoUsuario);
    $farmaceuticoId = farmaceuticoTeste($farmaceuticoUsuario);

    DB::table('vinculos_farmaceutico')->insert([
        'id_entidade' => $entidade->id,
        'id_farmaceutico' => $farmaceuticoId,
        'tipo_vinculo' => 'Responsavel_Tecnico',
        'status_vinculo' => 'Aceito',
        'date_inicio' => now()->toDateString(),
        'data_fim' => null,
    ]);

    $this->actingAs($farmaceuticoUsuario)
        ->get(route('estoque-medicamento', $entidade))
        ->assertOk();
});

test('usuario nao dono nao edita ponto de coleta de outro usuario', function () {
    $dono = usuarioTeste();
    $outroUsuario = usuarioTeste();
    $entidade = entidadeTeste($dono);

    $this->actingAs($outroUsuario)
        ->get(route('pontos-coleta.edit', $entidade))
        ->assertForbidden();
});
