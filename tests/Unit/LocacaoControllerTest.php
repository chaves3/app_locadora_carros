<?php

namespace Tests\Feature;

use App\Models\Carro;
use App\Models\Cliente;
use App\Models\Locacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocacaoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_locacoes()
    {
        Locacao::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/locacao');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_create_locacao()
    {
        $carro = Carro::factory()->create();
        $cliente = Cliente::factory()->create();

        $dados = [
            'carro_id' => $carro->id,
            'cliente_id' => $cliente->id,
            'data_inicio_periodo' => '2023-10-01',
            'data_final_previsto_periodo' => '2023-10-10',
            'valor_diaria' => 150.00,
            'km_inicial' => 1000,
        ];

        $response = $this->postJson('/api/v1/locacao', $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment($dados);

        // ALTERADO: 'locacaos' -> 'locacoes' (ou o nome real da sua tabela)
        $this->assertDatabaseHas('locacoes', $dados);
    }

    public function test_cannot_create_locacao_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/locacao', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['carro_id', 'cliente_id', 'data_inicio_periodo']);
    }

    public function test_can_show_locacao()
    {
        $locacao = Locacao::factory()->create();

        $response = $this->getJson("/api/v1/locacao/{$locacao->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $locacao->id,
                     'carro_id' => $locacao->carro_id,
                 ]);
    }

    public function test_show_returns_404_if_locacao_not_found()
    {
        $response = $this->getJson('/api/v1/locacao/99999');

        $response->assertStatus(404)
                 ->assertJson(['erro' => 'Recurso pesquisado não existe']);
    }

    public function test_can_update_locacao()
    {
        $locacao = Locacao::factory()->create();
        $novoCarro = Carro::factory()->create();

        $dadosAtualizados = [
            'carro_id' => $novoCarro->id,
            'cliente_id' => $locacao->cliente_id,
            'data_inicio_periodo' => '2023-11-01',
            'data_final_previsto_periodo' => '2023-11-15',
            'valor_diaria' => 200.00,
            'km_inicial' => 1500,
            'data_final_realizado_periodo' => '2023-11-15',
            'km_final' => 1800,
        ];

        $response = $this->putJson("/api/v1/locacao/{$locacao->id}", $dadosAtualizados);

        $response->assertStatus(200)
                 ->assertJsonFragment($dadosAtualizados);

        $this->assertDatabaseHas('locacoes', $dadosAtualizados);
    }

    public function test_can_partially_update_locacao()
    {
        $locacao = Locacao::factory()->create();

        $dadosParciais = [
            'km_final' => 2000,
            'data_final_realizado_periodo' => '2023-12-01',
        ];

        $response = $this->patchJson("/api/v1/locacao/{$locacao->id}", $dadosParciais);

        $response->assertStatus(200)
                 ->assertJsonFragment($dadosParciais);

        $this->assertDatabaseHas('locacoes', array_merge(['id' => $locacao->id], $dadosParciais));
    }

    public function test_can_delete_locacao()
    {
        $locacao = Locacao::factory()->create();

        $response = $this->deleteJson("/api/v1/locacao/{$locacao->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $locacao->id]);

        $this->assertDatabaseMissing('locacoes', ['id' => $locacao->id]);
    }

    public function test_delete_returns_404_if_locacao_not_found()
    {
        $response = $this->deleteJson('/api/v1/locacao/99999');

        $response->assertStatus(404)
                 ->assertJson(['erro' => 'Recurso deletado não existe']);
    }
}