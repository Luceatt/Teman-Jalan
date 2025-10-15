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
        Schema::create('friendships', function (Blueprint $table) {
            $table->id('friendship_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('friend_id');
            $table->string('status')->default('pending');
            $table->integer('times_together')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
