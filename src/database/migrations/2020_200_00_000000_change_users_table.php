<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:24 AM
 * Copyright (c) 2020. Powered by iamir.net
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
            $table->string('password')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();


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
            $table->string('mobile')->nullable()->after('country');
            $table->string('lang')->nullable()->default('en')->after('mobile');

            $table->string('role')->default('user')->after('mobile'); // ['guest', 'user', 'admin']

            $table->string('meta')->nullable()->after('remember_token');
            $table->string('log')->nullable()->after('meta');
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
            $table->string('email')->nullable(false)->change();
       });
    }
}
