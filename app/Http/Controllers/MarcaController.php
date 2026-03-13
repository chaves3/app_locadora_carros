<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);

         $marcas = array();
         if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
         }else{
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
         }

            if($request->has('filtro')){
                $marcaRepository->filtro($request->filtro);
            }
            
        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelo')->get(); 
        return response()->json($marcaRepository->getResultadoPaginado(4), 200);
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
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
    
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
        ]);

        // $marca =  $this->marca->create($request->all());
        return response()->json($marca, 201);
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
        
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404); //json
        }
        return response()->json($marca, 200);
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
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['erro' => 'Recurso editado não existe'], 404); //json
        }

            if ($request->method() === 'PATCH') {
                $regrasDinamicas = [];
                foreach ($marca->rules() as $input => $regras) {
                    if (array_key_exists($input, $request->all())) {
                        $regrasDinamicas[$input] = $regras;
                    }
                }
                $request->validate($regrasDinamicas, $marca->feedback());
            } else {
                $request->validate($marca->rules(), $marca->feedback());
            }



        //preencnehdno o bjeto $marca com todos dados so request

        $marca->fill($request->all());
        //se a imagem foi enchaminhada na requisição

        if($request->file('imagem')){
            //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $marca->imagem = $imagem_urn;
        }

        $marca->save();
        
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\integer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Recurso deletado não existe'], 404);
        }

        // Deleta a imagem se existir
        if($marca->imagem){
            Storage::disk('public')->delete($marca->imagem);
        }

        $marca->delete();
        return response()->json($marca, 200);
    }
}
