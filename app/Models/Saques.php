<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saques extends Model
{
    use HasFactory;

    protected $table = 'saques';

    protected $fillable = [
        'id_usuario',
        'nome_usuario',
        'valor',
        'chave_pix',
    ];
}

