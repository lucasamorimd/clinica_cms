<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Unidade;

class Funcionario extends Authenticatable
{
    use HasFactory, Notifiable;

    //public $timestamps = false;

    protected $fillable = [
        'nome_funcionario',
        'email',
        'password',
        'id_unidade',
        'tipo_perfil'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'id_unidade');
    }
}
