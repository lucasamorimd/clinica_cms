<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_servico',
        'tipo_servico',
        'tempo_estimado',
        'preco_servico',
        'descricao_servico',
        'foto_principal'
    ];
    public function unidades()
    {
        return $this->belongsToMany(Unidade::class, 'unidade_servicos', 'id_servico', 'id_unidade');
    }
    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'medico_servicos', 'id_medico', 'id_servico');
    }
}
