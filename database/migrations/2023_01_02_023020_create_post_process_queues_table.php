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
            $table->boolean('do_author_counts')->default(0);
            $table->boolean('do_sub_count')->default(0);
            $table->boolean('do_domain_count')->default(0);
            $table->timestamps();
            $table->foreign('id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_process_queues');
    }
};
