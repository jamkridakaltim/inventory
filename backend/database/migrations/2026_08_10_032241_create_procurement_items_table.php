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
    Schema::create('procurement_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('procurement_id')
            ->constrained('procurements')
            ->cascadeOnDelete();

        $table->foreignId('request_item_id')
            ->constrained('asset_request_items')
            ->cascadeOnDelete();

        $table->unsignedInteger('actual_quantity');

        $table->decimal('actual_amount', 15, 2);

        $table->date('received_at')->nullable();

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_items');
    }
};
