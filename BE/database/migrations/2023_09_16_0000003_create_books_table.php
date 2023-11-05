<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); 
            $table->string('isbn')->unique(); 
            $table->string('book_code')->unique(); 

            $table->unsignedBigInteger('id_category')->nullable();
            $table->string('title'); 
            $table->string('author')->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('publisher')->nullable();
            $table->double('price', 15, 2);
            $table->string('thumbnail')->nullable(); 
            $table->timestamps(); 

            $table->foreign('id_category')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
