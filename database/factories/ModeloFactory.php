<?php

namespace Database\Factories;

use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modelo>
 */
class ModeloFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'marca_id' => Marca::factory(),
            'nome' => $this->faker->word(),
            'numero_portas' => $this->faker->numberBetween(2, 5),
            'lugares' => $this->faker->numberBetween(2, 7),
            'air_bag' => $this->faker->boolean(),
            'abs' => $this->faker->boolean(),
            'imagem' => 'imagens/modelos/modelo.jpg',
        ];
    }
}
