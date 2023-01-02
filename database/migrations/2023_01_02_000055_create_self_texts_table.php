<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('self_texts', function (Blueprint $table) {
            $table->char('id', 8)->primary();
            $table->text('text');
            $table->text('html')->default(null)->nullable();
            $table->timestamps();
            $table->foreign('id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('self_texts');
    }
};
