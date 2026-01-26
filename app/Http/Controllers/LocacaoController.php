<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Models\Locacao;
use App\Models\Marca;
use App\Repositories\CarroRepository;
use App\Repositories\LocacaoRepository;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Storage;

class LocacaoController extends Controller
{

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $localcaoRepository = new LocacaoRepository($this->locacao);

            if($request->has('filtro')){
                $localcaoRepository->filtro($request->filtro);
            }
            
        if($request->has('atributos')){
            $localcaoRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelo')->get(); 
        return response()->json($localcaoRepository->getResultado(), 200);
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
            $request->validate($this->locacao->rules());
            
            $dados = $request->only([
                'carro_id',
                'cliente_id',
                'data_inicio_periodo',
                'data_final_previsto_periodo',
                'valor_diaria',
                'km_inicial'
            ]);
            
            // Adiciona apenas se não forem nulos
            if ($request->has('data_final_realizado_periodo') && $request->data_final_realizado_periodo) {
                $dados['data_final_realizado_periodo'] = $request->data_final_realizado_periodo;
            }
            
            if ($request->has('km_final') && $request->km_final) {
                $dados['km_final'] = $request->km_final;
            }
            
            $locacao = $this->locacao->create($dados);

            return response()->json($locacao, 201);
        }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404); //json
        }
        return response()->json($locacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
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
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'Recurso editado não existe'], 404); //json
        }

        if($request->method() === 'PATCH'){
                $regrasDinamicas = array();
                //percorrendo todas as regras definidas no model
                foreach ($locacao->rules() as $input => $regras) {
                    if (array_key_exists($input, $request->all())) {
                        $regrasDinamicas[$input] = $regras;
                    }
                }
        }

       
    
        //preencher o objeto marca com os dados do request
        $locacao->fill($request->all());
        $locacao->save();
      
        
        return response()->json($locacao, 200);
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
        $locacao = $this->locacao->find($id);
         if($locacao === null){
            return response()->json(['erro' => 'Recurso deletado não existe'], 404); //json
        }
        $locacao->delete();
        return response()->json($locacao, 200);
    }
}
