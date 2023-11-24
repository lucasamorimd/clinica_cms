<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_unidade',
        'crm',
        'nome_medico',
        'area_atuacao',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'id_unidade');
    }
    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'medico_servicos', 'id_medico', 'id_servico');
    }
    public function unidades()
    {
        return $this->belongsToMany(Unidade::class, 'unidade_medicos', 'id_unidade', 'id_medico');
    }
}
