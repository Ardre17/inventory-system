<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('product_raw_materials', function (Blueprint $table) {
            $table->decimal('stock', 10, 4)->default(0)->after('quantity_per_unit');
        });
    }
    public function down(): void {
        Schema::table('product_raw_materials', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};