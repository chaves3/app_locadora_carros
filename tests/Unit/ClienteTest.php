<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_criacao_estao_corretas()
    {
        $cliente = new Cliente();
        $regras = $cliente->rules();

        $this->assertEquals([
            'nome' => 'required',
        ], $regras);
    }

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_atualizacao_estao_corretas()
    {
        // Para update, a regra deve ser a mesma, pois não há unique
        $cliente = Cliente::factory()->create();
        $regras = $cliente->rules();

        $this->assertEquals([
            'nome' => 'required',
        ], $regras);
    }

    /** @test */
    public function verifica_mensagens_de_feedback()
    {
        $cliente = new Cliente();
        $feedback = $cliente->feedback();

        $this->assertArrayHasKey('required', $feedback);
        $this->assertEquals('O campo :attribute é obrigatório', $feedback['required']);
    }

    /** @test */
    public function verifica_relacionamento_com_modelo()
    {
        $cliente = new Cliente();
        $relacionamento = $cliente->modelo();

        // Verifica se é um relacionamento BelongsTo (Cliente pertence a Modelo)
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relacionamento);
        // A chave estrangeira padrão seria 'modelo_id', mas como o método chama 'modelo', pode ser 'modelo_id'
        // Se no model o método está como 'modelo', a FK é 'modelo_id'
        $this->assertEquals('modelo_id', $relacionamento->getForeignKeyName());
    }
}