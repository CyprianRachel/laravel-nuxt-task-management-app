<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Automatyczne klucz główny
            $table->string('name'); // Kolumna na nazwę produktu
            $table->text('description')->nullable(); // Opcjonalny opis produktu
            $table->timestamps(); // Automatyczne kolumny `created_at` i `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products'); // Usuwanie tabeli
    }
};
