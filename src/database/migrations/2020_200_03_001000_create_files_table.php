<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->string('slug')->unique();
            $table->string('dir')->unique();
            $table->string('url')->unique();
            $table->string('mode');
            $table->string('type');
            $table->string('mime');
            $table->string('exec');
            $table->timestamps();
            $table->unique(['post_id', 'mode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
