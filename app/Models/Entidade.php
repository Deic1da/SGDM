<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entidade extends Model
{
    protected $table = 'entidades';

    public $timestamps = false;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'id_dono_entidade',
        'horario_funcionamento',
        'aceita_validade_curta',
        'status',
        'latitude',
        'longitude',
        'farmaceutico_rt',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'municipio',
        'estado',
    ];
}
