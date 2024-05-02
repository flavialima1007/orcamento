<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lancamento;

class FicOrcamentaria extends Model
{
    use HasFactory;
    protected $fillable = [
        'movimento_id',
        'dotacao_id',
        'descricao',
        'observacao',
        'data',
        'empenho',
        'debito',
        'credito',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function movimento(){
        return $this->belongsTo(Movimento::class);
    }

    public function dotacao(){
        return $this->belongsTo(DotOrcamentaria::class);
    }

    public function setDebitoAttribute($value){
        $this->attributes['debito'] = str_replace(',','.',$value);
    }

    public function getDebitoAttribute($value){
        return number_format($value, 2, ',', '');
    }

    public function getDebitoRawAttribute(){
        if($this->debito){
            return (float)str_replace(',','.',$this->debito);
        }
    }

    public function setCreditoAttribute($value){
        $this->attributes['credito'] = str_replace(',','.',$value);
    }

    public function getCreditoAttribute($value){
        return number_format($value, 2, ',', '');
    }

    public function getCreditoRawAttribute(){
        if($this->credito){
            return (float)str_replace(',','.',$this->credito);
        }
    }

    public function getDataAttribute($data) {
        return implode('/',array_reverse(explode('-',$data)));
    }

    public function setDataAttribute($data) {
        $this->attributes['data'] = implode('-',array_reverse(explode('/',$data)));
    }

    public function contas(){
        return $this->belongsToMany(Conta::class)
                    ->withTimestamps();
    }
}
