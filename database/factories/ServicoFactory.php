<?php

namespace Database\Factories;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Servico::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $array = ['consultas', 'exames', 'procedimentos'];
        return [
            'nome_servico' => $this->faker->name(),
            'tipo_servico' => $array[rand(0, 2)],
            'tempo_estimado' => rand(1, 30),
            'preco_servico' => '10',
            'descricao_servico' => 'Teste',
            'foto_principal' => 'asdf'
        ];
    }
}
