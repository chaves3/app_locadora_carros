<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Carro;
use App\Models\Modelo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarroTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_criacao_estao_corretas()
    {
        $carro = new Carro();
        $regras = $carro->rules();

        $this->assertEquals([
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required',
        ], $regras);
    }

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_atualizacao_ignoram_o_proprio_id()
    {
        // Como as regras do Carro não têm unique, não precisamos testar isso.
        // Mas podemos testar que as regras permanecem as mesmas.
        $modelo = Modelo::factory()->create();
        $carro = Carro::factory()->create(['modelo_id' => $modelo->id]);

        $regras = $carro->rules();

        $this->assertEquals([
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required',
        ], $regras);
    }

    /** @test */
    public function verifica_relacionamento_com_modelo()
    {
        $carro = new Carro();
        $relacionamento = $carro->modelo();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relacionamento);
        $this->assertEquals('modelo_id', $relacionamento->getForeignKeyName());
    }

    /** @test */
    public function verifica_mensagens_de_feedback()
    {
        $carro = new Carro();
        $feedback = $carro->feedback();

        $this->assertArrayHasKey('required', $feedback);
        $this->assertEquals('O campo :attribute é obrigatório', $feedback['required']);
        // Outras mensagens, se houver
    }
}