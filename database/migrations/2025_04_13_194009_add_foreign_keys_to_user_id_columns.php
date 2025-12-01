<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUserIdColumns extends Migration
{
    public function up()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('adopter', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('doctor', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('donor', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('volunteer', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('adopter', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('doctor', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('donor', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('employee', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('volunteer', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
