<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->string('id', 16)->primary();
            $table->string('username')->unique();
            $table->integer('posts')->default(0);
            $table->integer('posts_18_plus')->default(0);
            $table->integer('total_score')->default(0);
            $table->integer('comments_on_posts')->default(0);
            $table->integer('subs_posted_to')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('authors');
    }
};
