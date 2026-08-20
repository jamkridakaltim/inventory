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
    Schema::create('procurements', function (Blueprint $table) {
        $table->id();

        $table->string('procurement_number', 100)->unique();

        $table->foreignId('asset_request_id')
            ->constrained('asset_requests')
            ->cascadeOnDelete();

        $table->date('procurement_date');

        $table->string('vendor_name', 200)->nullable();

        $table->enum('status', [
            'draft',
            'ordered',
            'received',
            'cancelled',
        ])->default('draft');

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
