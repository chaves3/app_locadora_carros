<?php

namespace Database\Factories;

use App\Models\Locacao;
use App\Models\Cliente;
use App\Models\Carro;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocacaoFactory extends Factory
{
    protected $model = Locacao::class;

    public function definition()
    {
        $dataInicio = $this->faker->dateTimeBetween('-1 month', 'now');
        $dataFimPrevisto = (clone $dataInicio)->modify('+'.rand(1,10).' days');
        $dataFimRealizado = $this->faker->optional(0.7)->dateTimeBetween($dataInicio, $dataFimPrevisto);

        return [
            'cliente_id' => Cliente::factory(),
            'carro_id' => Carro::factory(),
            'data_inicio_periodo' => $dataInicio,
            'data_final_previsto_periodo' => $dataFimPrevisto,
            'data_final_realizado_periodo' => $dataFimRealizado,
            'valor_diaria' => $this->faker->randomFloat(2, 50, 500),
            'km_inicial' => $this->faker->numberBetween(0, 100000),
            'km_final' => $this->faker->optional(0.8)->numberBetween(100000, 200000),
        ];
    }
}