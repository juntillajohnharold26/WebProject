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
            $table->string('profile_role')->nullable();
            $table->string('profile_location')->nullable();
            $table->text('profile_bio')->nullable();
            $table->string('profile_avatar')->nullable();
            $table->string('profile_status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_role',
                'profile_location',
                'profile_bio',
                'profile_avatar',
                'profile_status',
            ]);
        });
    }
};
