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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['inbound', 'outbound', 'transfer', 'adjustment']);
            $table->decimal('quantity', 12, 4);
            $table->decimal('quantity_before', 12, 4);
            $table->decimal('quantity_after', 12, 4);
            $table->decimal('unit_cost', 12, 4)->nullable();
            $table->string('reason')->nullable();
            $table->string('reference_document')->nullable();
            $table->text('notes')->nullable();
            $table->string('lot_number', 100)->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamp('created_at');
            $table->foreignId('inventory_item_id');
            $table->foreignId('warehouse_id');
            $table->foreignId('warehouse_bin_id');
            $table->foreignId('nullable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
