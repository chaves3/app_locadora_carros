<?php

namespace Database\Factories;

use App\Models\Carro;
use App\Models\Modelo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarroFactory extends Factory
{
    protected $model = Carro::class;

    public function definition()
    {
        return [
            'modelo_id' => Modelo::factory(),
            'placa' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{4}'),
            'disponivel' => $this->faker->boolean(),
            'km' => $this->faker->numberBetween(0, 200000),
        ];
    }
}