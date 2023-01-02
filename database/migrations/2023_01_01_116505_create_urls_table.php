<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->char('id', 8)->primary();
            $table->unsignedBigInteger('domain_id');
            $table->string('main');
            $table->string('dest')->default(null)->nullable();
            $table->string('thumbnail')->default(null)->nullable();
            $table->string('other')->default(null)->nullable();
            $table->integer('size')->default(null)->nullable();
            $table->integer('height')->default(null)->nullable();
            $table->integer('width')->default(null)->nullable();
            $table->integer('duration')->default(null)->nullable();
            $table->integer('bitrate')->default(null)->nullable();
            $table->boolean('downloaded')->default(null)->nullable();
            $table->boolean('is_unique')->default(null)->nullable();
            $table->timestamps();
            $table->foreign('id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('urls');
    }
};
