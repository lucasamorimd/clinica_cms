<?php

namespace Database\Factories;

use App\Models\Unidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unidade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome_unidade' => $this->faker->name(),
            'endereco_unidade' => $this->faker->address(),
            'cidade_unidade' => $this->faker->city(),
            'estado_unidade' => $this->faker->state(),
            'telefone_unidade' => $this->faker->phoneNumber(),
            'cnpj_unidade' => $this->faker->numberBetween()
        ];
    }
}
