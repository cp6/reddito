<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('post_trackings', function (Blueprint $table) {
            $table->id();
            $table->char('post_id', 8);
            $table->tinyInteger('status')->default(1);
            $table->boolean('locked')->default(0);
            $table->boolean('has_awards')->default(0);
            $table->integer('score')->default(null)->nullable();
            $table->integer('comments')->default(null)->nullable();
            $table->integer('awards')->default(null)->nullable();
            $table->integer('cross_posts')->default(null)->nullable();
            $table->float('upvote_ratio')->default(null)->nullable();
            $table->integer('minutes_since_posted')->default(null)->nullable();
            $table->timestamps();
            $table->unique(['post_id', 'created_at']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_trackings');
    }
};
