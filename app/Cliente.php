<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ["nome","email","data_nascimento","cpf"];

    public function endereco(){
        return $this->hasOne("App\Endereco");
}
}
