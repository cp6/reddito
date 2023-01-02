<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subs', function (Blueprint $table) {
            $table->string('id', 16)->primary();
            $table->string('name')->unique();
            $table->integer('posts')->default(null)->nullable();
            $table->integer('posts_18_plus')->default(null)->nullable();
            $table->integer('total_score')->default(null)->nullable();
            $table->integer('comments_on_posts')->default(null)->nullable();
            $table->integer('subscribers')->default(null)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subs');
    }
};
