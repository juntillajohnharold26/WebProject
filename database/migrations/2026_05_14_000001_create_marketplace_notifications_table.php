<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_listing_id')->nullable()->constrained('template_listings')->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('body');
            $table->string('badge')->default('Update');
            $table->string('badge_class')->default('bg-primary');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_notifications');
    }
};
