<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('link_flairs', function (Blueprint $table) {
            $table->string('id', 124)->primary();
            $table->string('type')->nullable()->default(null);
            $table->string('text')->nullable()->default(null);
            $table->string('css_class')->nullable()->default(null);
            $table->string('text_color')->nullable()->default(null);
            $table->string('richtext_1')->nullable()->default(null);
            $table->string('richtext_2')->nullable()->default(null);
            $table->string('richtext_3')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('link_flairs');
    }
};
