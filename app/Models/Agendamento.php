<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
}
