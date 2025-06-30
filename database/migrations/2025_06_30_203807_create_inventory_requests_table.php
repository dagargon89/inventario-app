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
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->date('event_date_start');
            $table->date('event_date_end');
            $table->enum('status', ['pending', 'approved', 'dispatched', 'completed', 'cancelled'])->default('pending');
            $table->text('notes_requester')->nullable();
            $table->text('notes_approver')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('approved_by:nullable_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_requests');
    }
};
