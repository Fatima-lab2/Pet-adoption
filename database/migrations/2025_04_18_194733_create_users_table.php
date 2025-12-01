<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('role_id')->nullable()->after('password'); // Add role_id after password
            $table->foreign('role_id')->references('role_id')->on('role')->onDelete('set null'); // Add foreign key constraint
            
            $table->string('image')->nullable()->after('role_id'); // Add image column after role_id
            $table->string('phone_number')->nullable()->after('email'); // Add phone_number after email
            $table->date('date_of_birth')->nullable()->after('phone_number'); // Add date_of_birth after phone_number
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id'); // Drop role_id column
            $table->dropColumn('image'); // Drop image column
            $table->dropColumn('phone_number'); // Drop phone_number column
            $table->dropColumn('date_of_birth'); // Drop date_of_birth column
        });
    }
};
