<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 11/4/20, 10:54 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropColumn('email');
            $table->string('password')->nullable()->change();
            $table->string('name')->nullable()->change();


            $table->unsignedBigInteger('avatar_id')->nullable()->after('id');
            $table->foreign('avatar_id')->references('id')->on('posts');

            if (iconfig('database.migrations.users.agent')){
                $table->unsignedBigInteger('agent_id')->nullable()->after('id');
                $table->foreign('agent_id')->references('id')->on('users');
            }

            if (iconfig('database.migrations.users.creator')){
                $table->unsignedBigInteger('creator_id')->nullable()->after('id');
                $table->foreign('creator_id')->references('id')->on('users');
            }

            $table->string('family')->nullable()->after('name');
            $table->string('gender')->nullable()->after('family'); // ['male', 'female']
            $table->string('username')->unique()->nullable()->after('gender');
            $table->string('country')->nullable()->after('email');
            $table->string('website')->nullable()->after('country');
            $table->string('lang')->nullable()->default('en')->after('website');

            $table->string('role')->default('user')->after('lang'); // ['guest', 'user', 'admin']
            $table->string('log')->nullable()->after('role');
            $table->string('status')->default('active')->after('log'); // ['waiting', 'active', 'block']
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('mobile');
            $table->dropColumn('username');
            $table->dropColumn('gender');
            $table->dropColumn('status');
            $table->dropColumn('type');
            $table->dropColumn('groups');
            $table->dropColumn('avatar_id');
            $table->dropColumn('family');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->string('email');
       });
    }
}
