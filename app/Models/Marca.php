<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable =  ['nome', 'imagem'];


    public function rules(){
        return  [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,dock,xlsx,pdf,ppt,jpeg,mp3',
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

    public function modelos(){
        //Um Marca pode ter muitos modelos

        return $this->hasMany(Modelo::class);
    }
}
