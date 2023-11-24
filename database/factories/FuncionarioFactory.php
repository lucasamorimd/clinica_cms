<?php

namespace Database\Factories;

use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuncionarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Funcionario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome_funcionario' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
