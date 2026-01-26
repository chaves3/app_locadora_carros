<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Models\Cliente;
use App\Models\Marca;
use App\Repositories\CarroRepository;
use App\Repositories\ClienteRepository;
use App\Repositories\MarcaRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $clienteRepository = new ClienteRepository($this->cliente);

         $clientes = array();


            if($request->has('filtro')){
                $clienteRepository->filtro($request->filtro);
            }
            
        if($request->has('atributos')){
            $clienteRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelo')->get(); 
        return response()->json($clienteRepository->getResultado(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //$marca = Marca::create($request->all());
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
    
        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);

        // $marca =  $this->marca->create($request->all());
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404); //json
        }
        return response()->json($cliente, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        // $marca->update($request->all());
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Recurso editado não existe'], 404); //json
        }

        if($request->method() === 'PATCH'){
                $regrasDinamicas = array();
                //percorrendo todas as regras definidas no model
                foreach ($cliente->rules() as $input => $regras) {
                    if (array_key_exists($input, $request->all())) {
                        $regrasDinamicas[$input] = $regras;
                    }
                }
            $request->validatey($cliente->rules(), $cliente->feedback());
        }else{
            $request->validate($cliente->rules(), $cliente->feedback()); 
        }

       
    
        //preencher o objeto marca com os dados do request
        $cliente->fill($request->all());
        $cliente->save();
      
        
        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\integer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);
         if($cliente === null){
            return response()->json(['erro' => 'Recurso deletado não existe'], 404); //json
        }
        $cliente->delete();
        return response()->json($cliente, 200);
    }
}
