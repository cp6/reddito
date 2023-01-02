<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->char('id', 8)->primary();
            $table->string('author_id', 16);
            $table->string('sub_id', 16);
            $table->unsignedBigInteger('domain_id')->default(null)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_self');
            $table->boolean('over_18');
            $table->boolean('locked')->default(0);
            $table->boolean('has_awards')->default(0);
            $table->integer('score')->default(null)->nullable();
            $table->integer('comments')->default(null)->nullable();
            $table->integer('awards')->default(null)->nullable();
            $table->integer('cross_posts')->default(null)->nullable();
            $table->float('upvote_ratio')->default(null)->nullable();
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('sub_id')->references('id')->on('subs')->onDelete('cascade');
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
