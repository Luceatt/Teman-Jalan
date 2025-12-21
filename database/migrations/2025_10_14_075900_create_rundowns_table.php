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
        Schema::create('rundowns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('status')->default('draft'); // draft, published, completed, cancelled
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For storing additional data like map settings, etc.
            $table->boolean('is_public')->default(false);
            $table->string('created_by')->nullable(); // User who created the rundown
            $table->timestamps();

            $table->index(['date', 'status']);
            $table->index(['is_public', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rundowns');
    }
};