<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplace_notifications', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->nullable()->after('badge_class');
            $table->decimal('total', 10, 2)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_notifications', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'total']);
        });
    }
};
