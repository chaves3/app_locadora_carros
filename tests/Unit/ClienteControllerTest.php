<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_retorna_lista_de_clientes()
    {
        Cliente::factory()->count(3)->create();

        $response = $this->getJson(route('cliente.index')); // nome da rota: cliente.index

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function index_pode_filtrar_clientes()
    {
        Cliente::factory()->create(['nome' => 'João Silva']);
        Cliente::factory()->create(['nome' => 'Maria Souza']);
        Cliente::factory()->create(['nome' => 'Carlos Santos']); // ← corrigido

        $response = $this->getJson(route('cliente.index', ['filtro' => 'nome:like:%Jo%']));

        $response->assertStatus(200)
                ->assertJsonCount(1)
                ->assertJsonFragment(['nome' => 'João Silva']);
    }

    /** @test */
    public function store_cria_um_novo_cliente()
    {
        $dados = [
            'nome' => 'Cliente Teste',
        ];

        $response = $this->postJson(route('cliente.store'), $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nome' => 'Cliente Teste']);

        $this->assertDatabaseHas('clientes', ['nome' => 'Cliente Teste']);
    }

    /** @test */
    public function store_falha_com_dados_invalidos()
    {
        $response = $this->postJson(route('cliente.store'), []); // sem nome

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nome']);
    }

    /** @test */
    public function show_retorna_cliente_especifico()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->getJson(route('cliente.show', ['cliente' => $cliente->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $cliente->id, 'nome' => $cliente->nome]);
    }

    /** @test */
    public function show_retorna_404_quando_cliente_nao_existe()
    {
        $response = $this->getJson(route('cliente.show', ['cliente' => 9999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function update_atualiza_cliente_completo()
    {
        $cliente = Cliente::factory()->create(['nome' => 'Nome Antigo']);

        $dados = [
            'nome' => 'Nome Atualizado',
        ];

        $response = $this->putJson(route('cliente.update', ['cliente' => $cliente->id]), $dados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome' => 'Nome Atualizado']);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nome' => 'Nome Atualizado'
        ]);
    }

    /** @test */
    public function update_atualiza_parcialmente_com_patch()
    {
        $cliente = Cliente::factory()->create(['nome' => 'Nome Antigo']);

        $response = $this->patchJson(route('cliente.update', ['cliente' => $cliente->id]), [
            'nome' => 'Nome Patch'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome' => 'Nome Patch']);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nome' => 'Nome Patch'
        ]);
    }

    /** @test */
    public function destroy_remove_cliente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->deleteJson(route('cliente.destroy', ['cliente' => $cliente->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome' => $cliente->nome]); // o controller retorna o cliente deletado

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    /** @test */
    public function destroy_retorna_404_quando_cliente_nao_existe()
    {
        $response = $this->deleteJson(route('cliente.destroy', ['cliente' => 9999]));

        $response->assertStatus(404);
    }
}