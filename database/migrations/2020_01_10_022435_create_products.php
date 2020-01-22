<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned()->index();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('designation')->nullable();
            $table->string('position')->nullable();
            $table->string('couleur')->nullable();
            $table->string('longueur')->nullable();
            $table->string('largeur')->nullable();
            $table->string('hauteur')->nullable();
            $table->string('epaisseur')->nullable();
            $table->string('poids')->nullable();
            $table->string('prix_uni')->nullable();
            $table->string('code_produit')->nullable();
            $table->string('code_douanier')->nullable();
            $table->string('port_sortie')->nullable();
            $table->string('fournisseur')->nullable();
            $table->string('image')->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
