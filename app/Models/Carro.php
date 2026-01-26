<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;


    protected $fillable =  ['modelo_id', 'placa', 'disponivel', 'km'];


    public function rules(){
        return  [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required',
        ];
    }

    public function feedback(){
            return  [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => "A marca já está cadastrada",
            'nome.min' => "A marca deve ter no mínimo 3 caracteres",
        ];
    }

    public function modelo(){
        //Um Marca pode ter muitos modelos

        return $this->belongsTo(Modelo::class);
    }
}
