<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_items', function (Blueprint $table) {
            $table->unsignedInteger('actual_quantity')->nullable()->change();

            $table->decimal('actual_amount', 15, 2)->nullable()->change();

            $table->date('received_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('procurement_items', function (Blueprint $table) {
            $table->unsignedInteger('actual_quantity')->nullable(false)->change();

            $table->decimal('actual_amount', 15, 2)->nullable(false)->change();

            $table->date('received_at')->nullable(false)->change();
        });
    }
};