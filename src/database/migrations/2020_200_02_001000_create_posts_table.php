<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('creator_id');
            $table->unsignedInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('posts');
            $table->string('slug')->nullable()->unique();
            $table->string('url')->nullable()->unique();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->text('summary')->nullable();
            $table->string('type')->default('post');
            $table->string('status')->default('draft');
            $table->integer('order')->nullable();
            $table->text('meta')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
