<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('nome', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->string('cpfcnpj');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('id_produto');
            $table->unsignedBigInteger('id_vendedor');
            $table->unsignedBigInteger('id_lista');
            $table->decimal('valor', 10, 2);
            $table->string('ficha_associativa')->nullable();
            $table->string('documento_com_foto')->nullable();
            $table->string('cartao_cnpj')->nullable();
            $table->string('consulta')->nullable();
            $table->string('documento_final')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vendas');
    }
};
