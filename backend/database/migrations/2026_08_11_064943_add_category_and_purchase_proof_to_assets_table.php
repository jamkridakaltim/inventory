<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('asset_name')
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('purchase_proof_number', 100)
                ->nullable()
                ->after('purchase_date');

            $table->string('purchase_proof', 255)
                ->nullable()
                ->after('purchase_proof_number');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id',
                'purchase_proof_number',
                'purchase_proof',
            ]);
        });
    }
};