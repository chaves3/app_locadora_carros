<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Carro;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarroControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_retorna_lista_de_carros()
    {
        Carro::factory()->count(5)->create();

        $response = $this->getJson(route('carro.index'));

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    /** @test */
    public function index_pode_filtrar_carros()
    {
        $marca = Marca::factory()->create();
        $modelo1 = Modelo::factory()->create(['marca_id' => $marca->id, 'nome' => 'Fusca']);
        $modelo2 = Modelo::factory()->create(['marca_id' => $marca->id, 'nome' => 'Gol']);

        Carro::factory()->create(['modelo_id' => $modelo1->id, 'placa' => 'ABC1234']);
        Carro::factory()->create(['modelo_id' => $modelo2->id, 'placa' => 'DEF5678']);

        $response = $this->getJson(route('carro.index', ['filtro' => 'placa:like:%123%']));

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['placa' => 'ABC1234']);
    }

    /** @test */
    public function index_pode_selecionar_atributos_especificos()
    {
        Carro::factory()->create();

        $response = $this->getJson(route('carro.index', ['atributos' => 'id,placa,km']));

        $response->assertStatus(200);
        $carro = $response->json()[0];
        $this->assertArrayHasKey('id', $carro);
        $this->assertArrayHasKey('placa', $carro);
        $this->assertArrayHasKey('km', $carro);
        $this->assertArrayNotHasKey('modelo_id', $carro);
        $this->assertArrayNotHasKey('disponivel', $carro);
    }

    /** @test */
    public function index_pode_incluir_modelo_relacionado()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id, 'nome' => 'Fusca']);
        Carro::factory()->create(['modelo_id' => $modelo->id]);

        $response = $this->getJson(route('carro.index', ['atributos_modelo' => 'id,nome']));

        $response->assertStatus(200);
        $carro = $response->json()[0];
        $this->assertArrayHasKey('modelo', $carro);
        $this->assertArrayHasKey('id', $carro['modelo']);
        $this->assertArrayHasKey('nome', $carro['modelo']);
        $this->assertEquals('Fusca', $carro['modelo']['nome']);
    }

    /** @test */
    public function store_cria_um_novo_carro()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);

        $dados = [
            'modelo_id' => $modelo->id,
            'placa' => 'XYZ9876',
            'disponivel' => true,
            'km' => 15000,
        ];

        $response = $this->postJson(route('carro.store'), $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment(['placa' => 'XYZ9876']);

        $this->assertDatabaseHas('carros', ['placa' => 'XYZ9876']);
    }

    /** @test */
    public function store_falha_com_dados_invalidos()
    {
        $response = $this->postJson(route('carro.store'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['placa', 'disponivel', 'km']); // modelo_id não é obrigatório
    }

    /** @test */
    public function show_retorna_carro_especifico_com_modelo()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);
        $carro = Carro::factory()->create(['modelo_id' => $modelo->id]);

        $response = $this->getJson(route('carro.show', ['carro' => $carro->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $carro->id])
                 ->assertJsonStructure(['modelo']);
    }

    /** @test */
    public function show_retorna_404_quando_carro_nao_existe()
    {
        $response = $this->getJson(route('carro.show', ['carro' => 9999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function update_atualiza_carro_completo()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);
        $carro = Carro::factory()->create(['modelo_id' => $modelo->id]);

        $dados = [
            'modelo_id' => $modelo->id,
            'placa' => 'NEW1234',
            'disponivel' => false,
            'km' => 20000,
        ];

        $response = $this->putJson(route('carro.update', ['carro' => $carro->id]), $dados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['placa' => 'NEW1234']);

        $this->assertDatabaseHas('carros', [
            'id' => $carro->id,
            'placa' => 'NEW1234',
            'km' => 20000,
        ]);
    }

    /** @test */
    public function update_atualiza_parcialmente_com_patch()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);
        $carro = Carro::factory()->create([
            'modelo_id' => $modelo->id,
            'placa' => 'OLD1234',
            'disponivel' => true,
            'km' => 10000,
        ]);

        $response = $this->patchJson(route('carro.update', ['carro' => $carro->id]), [
            'modelo_id' => $modelo->id,
            'placa' => 'PATCH999',
            'disponivel' => true,
            'km' => 10000,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['placa' => 'PATCH999']);

        $this->assertDatabaseHas('carros', [
            'id' => $carro->id,
            'placa' => 'PATCH999',
            'km' => 10000,
        ]);
    }

    /** @test */
    public function destroy_remove_carro()
    {
        $carro = Carro::factory()->create();

        $response = $this->deleteJson(route('carro.destroy', ['carro' => $carro->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $carro->id]);

        $this->assertDatabaseMissing('carros', ['id' => $carro->id]);
    }

    /** @test */
    public function destroy_retorna_404_quando_carro_nao_existe()
    {
        $response = $this->deleteJson(route('carro.destroy', ['carro' => 9999]));

        $response->assertStatus(404);
    }
}