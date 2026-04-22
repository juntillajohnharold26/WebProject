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
        Schema::create('template_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description');
            $table->enum('category', ['UI Kit', 'Dashboard', 'Landing', 'E-commerce', 'Mobile', 'Components', 'Icon Pack']);
            $table->json('tags');
            $table->decimal('price', 8, 2);
            $table->string('zip_path');
            $table->json('preview_images');
            $table->enum('status', ['active', 'draft', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_listings');
    }
};
