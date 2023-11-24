<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_unidade',
        'endereco_unidade',
        'cidade_unidade',
        'estado_unidade',
        'telefone_unidade',
        'cnpj_unidade'
    ];
    protected $primaryKey = 'id_unidade';
    protected $table = 'unidades';
    public function funcionario()
    {
        return $this->hasMany(Funcionario::class, 'id_unidade', 'id_unidade');
    }
    public function servicos()
    {
        return $this->belongsToMany(Servico::class);
    }
    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }
}
