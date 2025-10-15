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
        Schema::create('event_participants', function (Blueprint $table) {
            $table->increments('participant_id');
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('joined_at')->nullable();

            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
