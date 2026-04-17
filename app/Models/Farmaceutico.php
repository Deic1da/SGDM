<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmaceutico extends Model
{
    protected $table = 'farmaceuticos';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario_pf',
        'num_crf',
        'uf_crf',
        'status_profissional',
    ];
}
