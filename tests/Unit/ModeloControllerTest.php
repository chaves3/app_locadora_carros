<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ModeloControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_retorna_lista_de_modelos()
    {
        $marca = Marca::factory()->create();
        Modelo::factory()->count(3)->create(['marca_id' => $marca->id]);

        $response = $this->getJson(route('modelo.index')); // usa o nome da rota

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function index_pode_filtrar_modelos()
    {
        $marca = Marca::factory()->create();
        Modelo::factory()->create(['nome' => 'Fusca', 'marca_id' => $marca->id]);
        Modelo::factory()->create(['nome' => 'Gol', 'marca_id' => $marca->id]);

        $response = $this->getJson(route('modelo.index', ['filtro' => 'nome:like:%Fus%']));

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['nome' => 'Fusca']);
    }

    /** @test */
    public function store_cria_um_novo_modelo()
    {
        Storage::fake('public');
        $marca = Marca::factory()->create();

        $dados = [
            'marca_id' => $marca->id,
            'nome' => 'Fusca',
            'numero_portas' => 2,
            'lugares' => 5,
            'air_bag' => false,
            'abs' => false,
            'imagem' => UploadedFile::fake()->image('fusca.jpg')
        ];

        $response = $this->postJson(route('modelo.store'), $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nome' => 'Fusca']);

        Storage::disk('public')->assertExists($response->json('imagem'));
    }

    /** @test */
    public function store_falha_com_dados_invalidos()
    {
        $response = $this->postJson(route('modelo.store'), []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nome', 'imagem']); // ← sem 'marca_id'
    }

    /** @test */
    public function show_retorna_modelo_especifico()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);

        $response = $this->getJson(route('modelo.show', ['modelo' => $modelo->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $modelo->id]);
    }

    /** @test */
    public function show_retorna_404_quando_modelo_nao_existe()
    {
        $response = $this->getJson(route('modelo.show', ['modelo' => 9999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function update_atualiza_modelo_completo()
    {
        Storage::fake('public');
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);

        $novaImagem = UploadedFile::fake()->image('novo.jpg');

        $dados = [
            'marca_id' => $marca->id,
            'nome' => 'Fusca Atualizado',
            'numero_portas' => 4,
            'lugares' => 5,
            'air_bag' => true,
            'abs' => true,
            'imagem' => $novaImagem
        ];

        $response = $this->putJson(route('modelo.update', ['modelo' => $modelo->id]), $dados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome' => 'Fusca Atualizado']);

        Storage::disk('public')->assertMissing($modelo->imagem);
        Storage::disk('public')->assertExists($response->json('imagem'));
    }

    /** @test */
public function update_atualiza_parcialmente_com_patch()
{
    $marca = Marca::factory()->create();
    $modelo = Modelo::factory()->create([
        'marca_id' => $marca->id,
        'nome' => 'Fusca',
        'numero_portas' => 2,
        'lugares' => 5,
        'air_bag' => false,
        'abs' => false,
        'imagem' => 'imagens/modelos/test.jpg' // imagem fictícia
    ]);

    $response = $this->patchJson(route('modelo.update', ['modelo' => $modelo->id]), [
        'nome' => 'Fusca Patch',
        'marca_id' => $marca->id,
        'numero_portas' => 2,
        'lugares' => 5,
        'air_bag' => false,
        'abs' => false,
        // não envia imagem para manter a original
    ]);

    $response->assertStatus(200)
             ->assertJsonFragment(['nome' => 'Fusca Patch']);

    $this->assertDatabaseHas('modelos', [
        'id' => $modelo->id,
        'nome' => 'Fusca Patch'
    ]);
}

    /** @test */
    public function destroy_remove_modelo()
    {
        Storage::fake('public');
        $imagem = UploadedFile::fake()->image('fusca.jpg');
        $caminho = $imagem->store('imagens/modelos', 'public');
        
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create([
            'marca_id' => $marca->id,
            'imagem' => $caminho
        ]);

        $response = $this->deleteJson(route('modelo.destroy', ['modelo' => $modelo->id]));

        $response->assertStatus(200)
                 ->assertJson(['msg' => 'O modelo foi removido com sucesso!']);

        $this->assertDatabaseMissing('modelos', ['id' => $modelo->id]);
        Storage::disk('public')->assertMissing($caminho);
    }
}