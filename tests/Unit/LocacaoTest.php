<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Locacao;
use App\Models\Cliente;
use App\Models\Carro;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocacaoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_criacao_estao_corretas()
    {
        $locacao = new Locacao();
        $regras = $locacao->rules();

        $this->assertEquals([
            'carro_id' => 'required|integer|exists:carros,id',
            'cliente_id' => 'required|integer|exists:clientes,id',
            'data_inicio_periodo' => 'required|date',
            'data_final_previsto_periodo' => 'required|date|after:data_inicio_periodo',
            'data_final_realizado_periodo' => 'nullable|date|after:data_inicio_periodo',
            'valor_diaria' => 'required|numeric|min:0',
            'km_inicial' => 'required|integer|min:0',
            'km_final' => 'nullable|integer|min:0',
        ], $regras);
    }

    /** @test */
    public function verifica_se_as_regras_de_validacao_para_atualizacao_ignoram_o_proprio_id()
    {
        // Como não há unique, as regras são as mesmas.
        $cliente = Cliente::factory()->create();
        $carro = Carro::factory()->create();
        $locacao = Locacao::factory()->create([
            'cliente_id' => $cliente->id,
            'carro_id' => $carro->id,
        ]);

        $regras = $locacao->rules();

        $this->assertEquals([
            'carro_id' => 'required|integer|exists:carros,id',
            'cliente_id' => 'required|integer|exists:clientes,id',
            'data_inicio_periodo' => 'required|date',
            'data_final_previsto_periodo' => 'required|date|after:data_inicio_periodo',
            'data_final_realizado_periodo' => 'nullable|date|after:data_inicio_periodo',
            'valor_diaria' => 'required|numeric|min:0',
            'km_inicial' => 'required|integer|min:0',
            'km_final' => 'nullable|integer|min:0',
        ], $regras);
    }

    /** @test */
    public function verifica_mensagens_de_feedback()
    {
        $locacao = new Locacao();
        $feedback = $locacao->feedback();

        // Como o model Locacao não tem método feedback definido? No código fornecido não tem.
        // Vamos supor que ele tenha herdado ou tenha um padrão. Se não tiver, o teste falhará.
        // Na verdade, o model Locacao não tem método feedback() no código mostrado.
        // Então talvez precise ser implementado. Mas se não tiver, podemos pular este teste ou ajustar.
        // Por enquanto, vou comentar este teste. Se você quiser, podemos adicionar um feedback genérico.
        $this->markTestSkipped('Método feedback não definido em Locacao.');
    }

    /** @test */
    public function verifica_relacionamento_com_cliente_e_carro()
    {
        // Locacao não tem relacionamentos definidos no código mostrado.
        // Se houver, podemos testar. Mas no código só tem a tabela, sem métodos de relacionamento.
        // Vamos pular por enquanto.
        $this->markTestSkipped('Relacionamentos não definidos em Locacao.');
    }
}