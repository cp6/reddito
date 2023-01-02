<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->string('id', 124)->primary();
            $table->string('title');
            $table->integer('price')->nullable()->default(null);
            $table->string('desc')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->string('icon_small')->nullable()->default(null);
            $table->boolean('gives_reward')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awards');
    }
};
