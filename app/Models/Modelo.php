<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;


    protected $fillable =  ['marca_id', 'nome', 'numero_portas', 'lugares', 'air_bag', 'abs', 'imagem'];


    public function rules(){
        return  [
            'marca_id' => 'exists:marcas,id',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,dock,xlsx,pdf,ppt,jpeg,mp3,jpg',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function feedback(){
            return  [
            'required' => 'O campo :attribute é obrigatório',
            'imagem.mimes' => "O arquivo sdever ser do tipo png o jpeg",
            'nome.unique' => "A marca já está cadastrada",
            'nome.min' => "A marca deve ter no mínimo 3 caracteres",
        ];
    }

    public function marca(){
        //Um modelo pertence a uma marca

        return $this->belongsTo(Marca::class);
    }

}
