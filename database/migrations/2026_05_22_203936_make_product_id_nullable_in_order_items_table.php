<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::statement('PRAGMA foreign_keys=OFF');

        DB::statement('CREATE TABLE order_items_new (
            "id" integer primary key autoincrement not null,
            "order_id" integer not null,
            "product_id" integer null,
            "raw_material_id" integer null,
            "quantity" numeric not null,
            "quantity_sent" integer not null default 0,
            "dispatch_status" varchar not null default "pending",
            "unit_price" numeric not null,
            "subtotal" numeric not null,
            "created_at" datetime,
            "updated_at" datetime,
            foreign key("order_id") references "orders"("id") on delete cascade,
            foreign key("product_id") references "products"("id") on delete cascade,
            foreign key("raw_material_id") references "raw_materials"("id") on delete set null
        )');

        DB::statement('INSERT INTO order_items_new
            SELECT id, order_id, product_id, raw_material_id, quantity,
                   quantity_sent, dispatch_status, unit_price, subtotal,
                   created_at, updated_at
            FROM order_items');

        DB::statement('DROP TABLE order_items');
        DB::statement('ALTER TABLE order_items_new RENAME TO order_items');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void {}
};
