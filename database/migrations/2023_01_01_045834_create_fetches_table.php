<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fetches', function (Blueprint $table) {
            $table->id();
            $table->integer('results')->default(null)->nullable();
            $table->integer('inserted')->default(null)->nullable();
            $table->integer('updated')->default(null)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fetches');
    }
};
