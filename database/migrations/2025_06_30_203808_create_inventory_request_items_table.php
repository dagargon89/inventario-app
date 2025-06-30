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
        Schema::create('inventory_request_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantity_requested', 12, 4);
            $table->decimal('quantity_dispatched', 12, 4)->default(0);
            $table->decimal('quantity_returned', 12, 4)->default(0);
            $table->decimal('quantity_missing', 12, 4)->default(0);
            $table->decimal('quantity_damaged', 12, 4)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('inventory_request_id');
            $table->foreignId('inventory_item_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_request_items');
    }
};
