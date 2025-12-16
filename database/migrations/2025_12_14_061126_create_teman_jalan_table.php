<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Users sudah ada, tidak perlu dibuat lagi
        // Kolom yang ada: id, name, email, password, profile_picture_url, role, remember_token, created_at, updated_at
        // Jika butuh kolom username, bisa ditambahkan nanti dengan migration terpisah

        // Tabel Places
        Schema::create('places', function (Blueprint $table) {
            $table->id('place_id');
            $table->string('name');
            $table->text('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Tabel Events
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name')->nullable();
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
        });

        // Tabel EventParticipants
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabel Activities
        Schema::create('activities', function (Blueprint $table) {
            $table->id('activity_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('place_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('order_number')->nullable();
            
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('place_id')->references('place_id')->on('places')->onDelete('set null');
        });

        // Tabel Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('expense_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedBigInteger('paid_by_user_id')->nullable();
            $table->timestamp('expense_date')->useCurrent();
            
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreign('activity_id')->references('activity_id')->on('activities')->onDelete('cascade');
            $table->foreign('paid_by_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Tabel ExpenseShares
        Schema::create('expense_shares', function (Blueprint $table) {
            $table->id('share_id');
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount_owed', 10, 2)->nullable();
            $table->boolean('is_settled')->default(false);
            
            $table->foreign('expense_id')->references('expense_id')->on('expenses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabel UserPlaceVisits
        Schema::create('user_place_visits', function (Blueprint $table) {
            $table->id('visit_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('place_id')->nullable();
            $table->integer('visit_count')->default(0);
            $table->date('last_visit_date')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('place_id')->references('place_id')->on('places')->onDelete('cascade');
        });

        // Tabel Friendships
        Schema::create('friendships', function (Blueprint $table) {
            $table->id('friendship_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('friend_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('times_together')->default(0);
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('friendships');
        Schema::dropIfExists('user_place_visits');
        Schema::dropIfExists('expense_shares');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('events');
        Schema::dropIfExists('places');
    }
};