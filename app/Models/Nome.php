<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nome extends Model
{
    use HasFactory;

    protected $table = 'nome';

    protected $fillable = [
        'nome',
        'cpfcnpj',
        'whatsapp',
        'email',
        'id_produto',
        'id_vendedor',
        'id_lista',
        'valor',
        'ficha_associativa',
        'documento_com_foto',
        'consulta',
        'updatedat',
        'createdat'
    ];

    public function lista()
    {
        return $this->belongsTo(Lista::class, 'id_lista');
    }
}
