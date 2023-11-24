<?php

namespace Database\Factories;

use App\Models\Medico;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MedicoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Medico::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'crm' => $this->faker->numberBetween(),
            'nome_medico' => $this->faker->name(),
            'area_atuacao' => $this->faker->name(),
            'foto_medico'   => Hash::make('foto') . 'jpg'
        ];
    }
}
