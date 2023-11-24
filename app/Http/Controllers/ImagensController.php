<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImagensController extends Controller
{
    public function imagemMedico($nome_pasta, $nome_arquivo)
    {
        $filepath = storage_path('app/public/medicos/' . $nome_pasta . '/' . $nome_arquivo);
        return response()->file($filepath);
    }
    public function imagensServico($nome_pasta_tipo, $nome_pasta_servico, $nome_arquivo)
    {
        $filepath = storage_path('app/public/servicos/' . $nome_pasta_tipo . '/' . $nome_pasta_servico . '/' . $nome_arquivo);

        return response()->file($filepath);
    }
}
