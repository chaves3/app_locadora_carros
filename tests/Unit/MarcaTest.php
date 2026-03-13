<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarcaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_criacao_estao_corretas()
    {
        $marca = new Marca();
        $regras = $marca->rules();

        $this->assertEquals([
            'nome' => 'required|unique:marcas,nome,|min:3',
            'imagem' => 'required|file|mimes:png,dock,xlsx,pdf,ppt,jpeg,mp3',
        ], $regras);
    }

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_atualizacao_ignoram_o_proprio_id()
    {
        $marca = Marca::factory()->create();

        $regras = $marca->rules();

        // Verifica que a regra unique ignora o próprio ID
        $this->assertStringContainsString('unique:marcas,nome,' . $marca->id, $regras['nome']);
    }

    /** @test */
    public function verifica_relacionamento_com_modelos()
    {
        $marca = new Marca();
        $relacionamento = $marca->modelos();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relacionamento);
        $this->assertEquals('marca_id', $relacionamento->getForeignKeyName());
    }

    /** @test */
    public function verifica_mensagens_de_feedback()
    {
        $marca = new Marca();
        $feedback = $marca->feedback();

        $this->assertArrayHasKey('required', $feedback);
        $this->assertArrayHasKey('imagem.mimes', $feedback);
        $this->assertArrayHasKey('nome.unique', $feedback);
        $this->assertArrayHasKey('nome.min', $feedback);

        $this->assertEquals('O campo :attribute é obrigatório', $feedback['required']);
        $this->assertEquals('O arquivo sdever ser do tipo png o jpeg', $feedback['imagem.mimes']);
        $this->assertEquals('A marca já está cadastrada', $feedback['nome.unique']);
        $this->assertEquals('A marca deve ter no mínimo 3 caracteres', $feedback['nome.min']);
    }
}