<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('awards_for_posts', function (Blueprint $table) {
            $table->id();
            $table->string('award_id', 124);
            $table->char('post_id', 8);
            $table->integer('count')->default(1);
            $table->timestamps();
            $table->unique(['award_id', 'post_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('award_id')->references('id')->on('awards')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('awards_for_posts');
    }
};
