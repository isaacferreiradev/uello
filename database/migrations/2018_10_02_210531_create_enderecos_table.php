<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->increments('id');
            $table->string("logradouro");
            $table->integer("numero");
            $table->string("complemento")->nullable();
            $table->string("bairro");
            $table->string("cep");
            $table->string("cidade");
            $table->string("longitude");
            $table->string("latitude");
            $table->unsignedInteger("cliente_id");

            $table->foreign("cliente_id")->on("clientes")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
}
