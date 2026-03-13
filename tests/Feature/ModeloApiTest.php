<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModeloApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_modelo_api_route_works()
    {
        $response = $this->get('/api/v1/modelo');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }

        public function test_marca_api_route_works()
    {
        $response = $this->get('/api/v1/marca');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

        public function test_locacao_api_route_works()
    {
        $response = $this->get('/api/v1/locacao');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }

        public function test_carro_api_route_works()
    {
        $response = $this->get('/api/v1/carro');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }


    public function test_cliente_api_route_works()
    {
        $response = $this->get('/api/v1/cliente');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }


}
