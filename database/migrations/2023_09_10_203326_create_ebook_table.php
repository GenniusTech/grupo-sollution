<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('ebook', function (Blueprint $table) {
            $table->id();
            $table->string('cpf');
            $table->string('email');
            $table->decimal('valor', 10, 2);
            $table->string('produto');
            $table->string('status');
            $table->string('id_pay');
            $table->integer('id_vendedor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebook');
    }
};
