<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus foreign key terlebih dahulu
        DB::statement("
            ALTER TABLE assets
            DROP FOREIGN KEY fk_assets_request_item
        ");

        // 2. Hapus UNIQUE index request_item_id
        DB::statement("
            ALTER TABLE assets
            DROP INDEX request_item_id
        ");

        // 3. Buat index biasa
        DB::statement("
            ALTER TABLE assets
            ADD INDEX idx_assets_request_item_id (request_item_id)
        ");

        // 4. Pasang kembali foreign key
        DB::statement("
            ALTER TABLE assets
            ADD CONSTRAINT fk_assets_request_item
            FOREIGN KEY (request_item_id)
            REFERENCES asset_request_items (id)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");
    }

    public function down(): void
    {
        // Hapus foreign key
        DB::statement("
            ALTER TABLE assets
            DROP FOREIGN KEY fk_assets_request_item
        ");

        // Hapus index biasa
        DB::statement("
            ALTER TABLE assets
            DROP INDEX idx_assets_request_item_id
        ");

        // Kembalikan UNIQUE index
        DB::statement("
            ALTER TABLE assets
            ADD UNIQUE INDEX request_item_id (request_item_id)
        ");

        // Pasang kembali foreign key
        DB::statement("
            ALTER TABLE assets
            ADD CONSTRAINT fk_assets_request_item
            FOREIGN KEY (request_item_id)
            REFERENCES asset_request_items (id)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");
    }
};