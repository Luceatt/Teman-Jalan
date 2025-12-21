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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('duration')->nullable(); // e.g., "2 hours", "30 minutes"
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->foreignId('rundown_id')->constrained('rundowns')->onDelete('cascade');
            $table->integer('order')->default(1); // For ordering activities in a rundown
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['rundown_id', 'order']);
            $table->index(['place_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};