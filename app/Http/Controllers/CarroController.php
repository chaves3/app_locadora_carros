<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Models\Marca;
use App\Repositories\CarroRepository;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Storage;

class CarroController extends Controller
{

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $carroRepository = new CarroRepository($this->carro);

         $carros = array();
         if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
         }else{
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
         }

            if($request->has('filtro')){
                $carroRepository->filtro($request->filtro);
            }
            
        if($request->has('atributos')){
            $carroRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelo')->get(); 
        return response()->json($carroRepository->getResultado(), 200);
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
        $request->validate($this->carro->rules(), $this->carro->feedback());
    
        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);

        // $marca =  $this->marca->create($request->all());
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        
        $carro = $this->carro->with('modelo')->find($id);
        if($carro === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404); //json
        }
        return response()->json($carro, 200);
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
        $carro = $this->carro->find($id);

        if($carro === null){
            return response()->json(['erro' => 'Recurso editado não existe'], 404); //json
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            foreach ($carro->rules() as $input => $regras) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regras;
                }
            }
            $request->validate($regrasDinamicas, $carro->feedback()); // ← usar $regrasDinamicas
        } else {
            $request->validate($carro->rules(), $carro->feedback());
        }
       
    
        //preencher o objeto marca com os dados do request
        $carro->fill($request->all());
        $carro->save();
      
        
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\integer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $carro = $this->carro->find($id);
         if($carro === null){
            return response()->json(['erro' => 'Recurso deletado não existe'], 404); //json
        }
        $carro->delete();
        return response()->json($carro, 200);
    }
}
