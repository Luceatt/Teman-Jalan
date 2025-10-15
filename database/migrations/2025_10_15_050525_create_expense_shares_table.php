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
        Schema::create('expense_shares', function (Blueprint $table) {
            $table->increments('share_id');
            $table->unsignedInteger('expense_id');
            $table->unsignedInteger('user_id');
            $table->decimal('amount_owed', 10, 2);
            $table->boolean('is_paid')->default(false);

            $table->foreign('expense_id')->references('expense_id')->on('expenses')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_shares');
    }
};
