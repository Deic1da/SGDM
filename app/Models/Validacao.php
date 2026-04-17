<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validacao extends Model
{
    protected $table = 'validacoes';

    public $timestamps = false;

    protected $fillable = [
        'id_medicamento_doacao',
        'id_farmaceutico_validante',
        'status_validacao',
        'motivo_rejeicao',
        'data_validacao',
    ];

    protected $casts = [
        'data_validacao' => 'datetime',
    ];
}
