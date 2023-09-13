<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory;

    protected $table = 'marketing';

    protected $fillable = [
        'id_produto',
        'id_user',
        'nome',
        'descricao',
        'arquivo',
    ];
}
