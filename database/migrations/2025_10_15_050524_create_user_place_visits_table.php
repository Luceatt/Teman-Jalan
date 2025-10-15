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
        Schema::create('user_place_visits', function (Blueprint $table) {
            $table->increments('visit_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('place_id');
            $table->integer('visit_count')->default(0);
            $table->dateTime('last_visit_date');

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('place_id')->references('place_id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_place_visits');
    }
};
