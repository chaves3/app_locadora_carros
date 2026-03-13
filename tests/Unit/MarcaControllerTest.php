<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MarcaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_retorna_lista_paginada_de_marcas()
    {
        Marca::factory()->count(10)->create();

        $response = $this->getJson(route('marca.index'));

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'links'])
                 ->assertJsonCount(4, 'data');
    }

    /** @test */
    public function index_pode_filtrar_marcas()
    {
        Marca::factory()->create(['nome' => 'Fiat']);
        Marca::factory()->create(['nome' => 'Ford']);
        Marca::factory()->create(['nome' => 'Chevrolet']);

        $response = $this->getJson(route('marca.index', ['filtro' => 'nome:like:%ia%']));

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['nome' => 'Fiat']);
    }

    /** @test */
    public function index_pode_selecionar_atributos_especificos()
    {
        Marca::factory()->create(['nome' => 'Fiat']);

        $response = $this->getJson(route('marca.index', ['atributos' => 'id,nome']));

        $response->assertStatus(200);
        $data = $response->json('data')[0];
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('nome', $data);
        $this->assertArrayNotHasKey('imagem', $data);
    }

    /** @test */
    public function index_pode_incluir_modelos_relacionados()
    {
        $marca = Marca::factory()->create();
        Modelo::factory()->count(2)->create(['marca_id' => $marca->id]);

        // Garante que os modelos foram criados
        $this->assertCount(2, Modelo::where('marca_id', $marca->id)->get());

        $response = $this->getJson(route('marca.index', ['atributos_modelos' => 'nome']));

        $response->assertStatus(200);
        $data = $response->json('data')[0];
        $this->assertArrayHasKey('modelos', $data);
    }

    /** @test */
    public function store_cria_uma_nova_marca()
    {
        Storage::fake('public');

        $dados = [
            'nome' => 'Marca Teste',
            'imagem' => UploadedFile::fake()->image('marca.jpg')
        ];

        $response = $this->postJson(route('marca.store'), $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nome' => 'Marca Teste']);

        Storage::disk('public')->assertExists($response->json('imagem'));
        $this->assertDatabaseHas('marcas', ['nome' => 'Marca Teste']);
    }

    /** @test */
    public function store_falha_com_dados_invalidos()
    {
        $response = $this->postJson(route('marca.store'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nome', 'imagem']);
    }

    /** @test */
    public function show_retorna_marca_especifica_com_modelos()
    {
        $marca = Marca::factory()->create();
        Modelo::factory()->count(3)->create(['marca_id' => $marca->id]);

        $response = $this->getJson(route('marca.show', ['marca' => $marca->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $marca->id])
                 ->assertJsonStructure(['modelos']);
        $this->assertCount(3, $response->json('modelos'));
    }

    /** @test */
    public function show_retorna_404_quando_marca_nao_existe()
    {
        $response = $this->getJson(route('marca.show', ['marca' => 9999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function update_atualiza_marca_completa()
    {
        Storage::fake('public');
        $marca = Marca::factory()->create(['nome' => 'Nome Antigo']);

        $novaImagem = UploadedFile::fake()->image('nova.jpg');

        $dados = [
            'nome' => 'Nome Atualizado',
            'imagem' => $novaImagem
        ];

        $response = $this->putJson(route('marca.update', ['marca' => $marca->id]), $dados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome' => 'Nome Atualizado']);

        Storage::disk('public')->assertMissing($marca->imagem);
        Storage::disk('public')->assertExists($response->json('imagem'));
    }

    /** @test */
    public function update_atualiza_parcialmente_com_patch()
    {
        $marca = Marca::factory()->create(['nome' => 'Nome Antigo', 'imagem' => 'imagens/marcas/antiga.jpg']);

        $response = $this->patchJson(route('marca.update', ['marca' => $marca->id]), [
            'nome' => 'Nome Patch'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome' => 'Nome Patch']);
        $this->assertDatabaseHas('marcas', [
            'id' => $marca->id,
            'nome' => 'Nome Patch',
            'imagem' => 'imagens/marcas/antiga.jpg'
        ]);
    }

    /** @test */
    public function destroy_remove_marca_e_imagem()
    {
        Storage::fake('public');
        $imagem = UploadedFile::fake()->image('marca.jpg');
        $caminho = $imagem->store('imagens', 'public');

        $marca = Marca::factory()->create(['imagem' => $caminho]);

        $response = $this->deleteJson(route('marca.destroy', ['marca' => $marca->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $marca->id]);

        $this->assertDatabaseMissing('marcas', ['id' => $marca->id]);
        Storage::disk('public')->assertMissing($caminho);
    }

    /** @test */
    public function destroy_retorna_404_quando_marca_nao_existe()
    {
        $response = $this->deleteJson(route('marca.destroy', ['marca' => 9999]));

        $response->assertStatus(404);
    }
}