<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->char('id', 8)->primary();
            $table->string('title');
            $table->string('cleaned_title')->default(null)->nullable();
            $table->boolean('was_cleaned')->default(0);
            $table->timestamps();
            $table->foreign('id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('titles');
    }
};
