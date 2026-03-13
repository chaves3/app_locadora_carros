<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function index(Request $request)
    {

        $modeloRepository = new ModeloRepository($this->modelo);

         $marcas = array();
         if($request->has('atributos_marca')){
            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
         }else{
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
         }

            if($request->has('filtro')){
                $modeloRepository->filtro($request->filtro);
            }
            
        if($request->has('atributos')){
            $modeloRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelo')->get(); 
        return response()->json($modeloRepository->getResultado(), 200);
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

        $request->validate($this->modelo->rules(), $this->modelo->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
    
        $modelo = $this->modelo->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'marca_id' => $request->marca_id,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,


        ]);

        // $marca =  $this->marca->create($request->all());
        return response()->json($modelo, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, $id)
{
    $modelo = $this->modelo->find($id);

    if($modelo === null){
        return response()->json(['erro' => 'Recurso editado não existe'], 404);
    }

    // Validação para PATCH
    if($request->method() === 'PATCH'){
        $regrasDinamicas = [];
        foreach ($modelo->rules() as $input => $regras) {
            if (array_key_exists($input, $request->all())) {
                $regrasDinamicas[$input] = $regras;
            }
        }
        $request->validate($regrasDinamicas, $modelo->feedback());
    } else {
        $request->validate($modelo->rules(), $modelo->feedback());
    }

    // Atualiza a imagem somente se um novo arquivo for enviado
    if($request->file('imagem')){
        // Remove a imagem antiga
        Storage::disk('public')->delete($modelo->imagem);
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        $modelo->imagem = $imagem_urn;
    }

    // Preenche os outros campos
    $modelo->fill($request->except('imagem')); // evita sobrescrever imagem com null
    $modelo->save();
    
    return response()->json($modelo, 200);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
      public function destroy(Request $request, $id)
        {
            //
            $modelo = $this->modelo->find($id);
            if($modelo === null){
                return response()->json(['erro' => 'Recurso deletado não existe'], 404); //json
            }

         
            Storage::disk('public')->delete($modelo->imagem);
           

            $modelo->delete();
            return response()->json(['msg' => 'O modelo foi removido com sucesso!'], 200);
        }
}
