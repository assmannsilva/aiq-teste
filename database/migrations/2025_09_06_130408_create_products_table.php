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
            $table->id();

            // Achei melhor colocar fakestore porque fica mais claro para nosso caso e mais intuitivo, mas poderia ser external_product_id ou algo assim
            // se fosse possível que essa API fosse trocada

            $table->string('fakestore_product_id')->unique();
            $table->string('title');
            $table->string('image_url');
            $table->integer('price_in_cents');

            // Achei melhor remover os ratings nessa tabela porque são dados mais voláteis
            // E o intuito dessa tabela é apenas ter um fallback caso a API externa esteja fora do ar e o cache expirado
            // Então é melhor não mostrar os dados do que mostrar dados possivelmente desatualizados
            // No entanto o mais problemático de estar desatualizado é o preço, porém é um dado obrigatório
            // Então preferi manter

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
