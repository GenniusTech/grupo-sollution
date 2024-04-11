<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->longText('descricao');
            $table->timestamps();
        });
    }

    public function down(): void {
        
    }
};
