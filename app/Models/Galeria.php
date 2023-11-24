<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_servico',
        'nome_foto'
    ];
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
