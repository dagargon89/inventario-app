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
        Schema::create('inventory_item_serials', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->enum('status', ['in_stock', 'out_of_stock', 'reserved'])->default('in_stock');
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
        Schema::dropIfExists('inventory_item_serials');
    }
};
