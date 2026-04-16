<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicamentoDoacao extends Model
{
    protected $table = 'medicamentos_doacao';

    public $timestamps = false;

    protected $fillable = [
        'id_doador',
        'nome_medicamento',
        'forma_farmaceutica',
        'condicao_embalagem',
        'data_validade',
        'status_doacao',
        'id_entidade_destino',
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];
}
