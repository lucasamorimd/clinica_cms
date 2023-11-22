<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use App\Models\Medico;
use App\Models\Servico;
use App\Models\Unidade;
use App\Models\User;
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
            $users =  User::factory(3)->has(Medico::factory()->for($unidade))->create();
            if ($users::class == Collection::class) {
                foreach ($users as $user) {
                    Servico::factory(2)->hasAttached($user->medico->unidade)->hasAttached($user->medico)->create();
                }
            } else {
                Servico::factory(2)->hasAttached($users->medico->unidade)->hasAttached($users->medico)->create();
            }
            User::factory(2)->has(Funcionario::factory()->for($unidade))->create();
        }
    }
}
