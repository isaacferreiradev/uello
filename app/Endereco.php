<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    public $timestamps = false;
    protected $fillable = ["logradouro","numero","complemento","bairro","cep","cidade","cliente_id","longitude","latitude"];

    public function cliente(){
        return $this->belongsTo("App/Cliente");
    }
}
