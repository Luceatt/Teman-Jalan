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
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('expense_id');
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('payer_id');
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->timestamp('expense_date')->useCurrent();

            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('payer_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
