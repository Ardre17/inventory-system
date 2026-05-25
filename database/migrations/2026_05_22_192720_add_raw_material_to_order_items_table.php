<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // SQLite no permite modificar columnas, recreamos la tabla
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('raw_material_id')->nullable()->after('product_id')->constrained('raw_materials')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['raw_material_id']);
            $table->dropColumn('raw_material_id');
        });
    }
};
