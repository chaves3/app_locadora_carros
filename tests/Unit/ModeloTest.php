<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModeloTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_criacao_estao_corretas()
    {
        $modelo = new Modelo();
        $regras = $modelo->rules();

        $this->assertEquals([
            'marca_id' => 'exists:marcas,id',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'nome' => 'required|unique:modelos,nome,|min:3', // vírgula extra
            'imagem' => 'required|file|mimes:png,dock,xlsx,pdf,ppt,jpeg,mp3,jpg',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ], $regras);
    }

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_atualizacao_ignoram_o_proprio_id()
    {
        $marca = Marca::factory()->create();
        $modelo = Modelo::factory()->create(['marca_id' => $marca->id]);

        $regras = $modelo->rules();

        // Agora com a vírgula antes do ID
        $this->assertStringContainsString('unique:modelos,nome,' . $modelo->id, $regras['nome']);
    }

    /** @test */
    public function verifica_relacionamento_com_marca()
    {
        $modelo = new Modelo();
        $relacionamento = $modelo->marca();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relacionamento);
        $this->assertEquals('marca_id', $relacionamento->getForeignKeyName());
    }

    /** @test */
    public function verifica_mensagens_de_feedback()
    {
        $modelo = new Modelo();
        $feedback = $modelo->feedback();

        $this->assertArrayHasKey('required', $feedback);
        $this->assertEquals('O campo :attribute é obrigatório', $feedback['required']);
    }
}