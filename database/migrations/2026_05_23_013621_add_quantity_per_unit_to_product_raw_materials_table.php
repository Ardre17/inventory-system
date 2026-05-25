<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('product_raw_materials', function (Blueprint $table) {
            $table->decimal('quantity_per_unit', 10, 4)->default(1)->after('raw_material_id');
        });
    }
    public function down(): void {
        Schema::table('product_raw_materials', function (Blueprint $table) {
            $table->dropColumn('quantity_per_unit');
        });
    }
};