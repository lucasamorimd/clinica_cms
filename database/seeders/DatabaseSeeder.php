<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use App\Models\Medico;
use App\Models\Servico;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $unidades = Unidade::factory(2)->create();
        foreach ($unidades as $unidade) {
            Funcionario::factory([
                'nome_funcionario' => 'Lucas Amorim',
                'email' => 'lucas.ad@hotmail.com',
                'password' => '$2y$10$WNzSBttwNJ5ZGlE3ojn7JORDfeCvhNrPH8d8VBGsteObQwklgZ/xK',
                'tipo_perfil' => 'administrador',
                'created_at' => now(),
                'updated_at' => now()
            ])->for($unidade)->create();
        }
    }
}
