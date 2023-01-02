<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('post_process_queues', function (Blueprint $table) {
            $table->char('id', 8)->primary();
            $table->boolean('increment_author_posts_count')->default(0);
            $table->boolean('increment_author_subs_count')->default(0);
            $table->boolean('increment_sub_posts_count')->default(0);
            $table->boolean('increment_domain_posts_count')->default(0);
            $table->boolean('increment_domain_unique_subs_count')->default(0);
            $table->timestamps();
            $table->foreign('id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_process_queues');
    }
};
