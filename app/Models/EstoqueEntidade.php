<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueEntidade extends Model
{
    protected $table = 'estoque_entidade';

    public $timestamps = false;

    protected $fillable = [
        'id_entidade',
        'id_validacao',
        'quantidade',
        'data_entrada',
        'status_estoque',
    ];

    protected $casts = [
        'data_entrada' => 'datetime',
    ];
}
