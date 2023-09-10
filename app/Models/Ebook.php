<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $table = 'ebook';

    protected $fillable = [
        'cpf',
        'email',
        'valor',
        'produto',
        'status',
        'id_pay',
        'id_vendedor',
    ];
}
