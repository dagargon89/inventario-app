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
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantity', 12, 4)->default(0.00);
            $table->decimal('quantity_reserved', 12, 4)->default(0.00);
            $table->decimal('low_stock_threshold', 12, 4)->nullable();
            $table->timestamp('last_movement_at')->nullable();
            $table->foreignId('inventory_item_id');
            $table->foreignId('warehouse_bin_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
