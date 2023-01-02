<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('link_flair_for_posts', function (Blueprint $table) {
            $table->id();
            $table->string('flair_id', 124);
            $table->char('post_id', 8);
            $table->timestamps();
            $table->unique(['flair_id', 'post_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('flair_id')->references('id')->on('link_flairs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('link_flair_for_posts');
    }
};
