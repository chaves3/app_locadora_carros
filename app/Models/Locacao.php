<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;
    protected $table = 'locacoes';
    protected $fillable = ['cliente_id', 
    'carro_id', 
    'data_inicio_periodo', 
    'data_final_previsto_periodo',
    'data_final_realizado_periodo',
    'valor_diaria',
    'km_inicial',
    'km_final',
];

    public function rules()
    {
        return [
            'carro_id' => 'required|integer|exists:carros,id',
            'cliente_id' => 'required|integer|exists:clientes,id',
            'data_inicio_periodo' => 'required|date',
            'data_final_previsto_periodo' => 'required|date|after:data_inicio_periodo',
            'data_final_realizado_periodo' => 'nullable|date|after:data_inicio_periodo', 
            'valor_diaria' => 'required|numeric|min:0',
            'km_inicial' => 'required|integer|min:0',
            'km_final' => 'nullable|integer|min:0' 
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'carro_id.exists' => 'O carro informado não existe',
            'cliente_id.exists' => 'O cliente informado não existe',
            'data_final_previsto_periodo.after' => 'A data final prevista deve ser posterior à data de início',
            'data_final_realizado_periodo.after' => 'A data final realizada deve ser posterior à data de início',
            'valor_diaria.min' => 'O valor da diária deve ser maior ou igual a zero',
            'km_inicial.min' => 'A quilometragem inicial deve ser maior ou igual a zero',
            'km_final.min' => 'A quilometragem final deve ser maior ou igual a zero',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }
}
