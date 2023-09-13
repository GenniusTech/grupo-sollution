<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produto');
            $table->unsignedBigInteger('id_user');
            $table->string('nome');
            $table->text('descricao');
            $table->string('arquivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing');
    }
};
