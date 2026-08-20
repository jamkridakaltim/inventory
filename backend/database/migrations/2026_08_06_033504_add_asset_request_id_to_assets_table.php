<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('asset_request_id')
                  ->nullable()
                  ->after('asset_code')
                  ->constrained('asset_requests')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['asset_request_id']);
            $table->dropColumn('asset_request_id');
        });
    }
};
