<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuplentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql-gys')->create('suplentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('beneficiario');
            $table->string('rfc')->unique();
           /*
            $table->string('clabe_interbancaria')->unique();
            $table->string('curp')->unique();
            $table->string('direccion');
            $table->string('municipio');
            $table->string('codigo_postal');
            */
            $table->timestamps();
            // Agregamos la clave foránea
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql-gys')->drop('suplentes');
    }
}
