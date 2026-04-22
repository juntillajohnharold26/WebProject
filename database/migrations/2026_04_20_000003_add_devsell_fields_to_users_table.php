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
            $table->boolean('devsell_active')->default(false);
            $table->timestamp('devsell_joined_at')->nullable();
            $table->string('devsell_display_name')->nullable();
            $table->string('devsell_store_name')->nullable();
            $table->string('devsell_specialty')->nullable();
            $table->string('devsell_portfolio')->nullable();
            $table->text('devsell_bio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'devsell_active',
                'devsell_joined_at',
                'devsell_display_name',
                'devsell_store_name',
                'devsell_specialty',
                'devsell_portfolio',
                'devsell_bio',
            ]);
        });
    }
};
