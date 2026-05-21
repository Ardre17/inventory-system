<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('sku');
            $table->string('lot')->nullable()->after('barcode');
            $table->enum('rotation', ['alta', 'media', 'baja'])->default('media')->after('lot');
            $table->date('production_date')->nullable()->after('rotation');
            $table->date('expiration_date')->nullable()->after('production_date');
            $table->decimal('boxes', 10, 2)->default(0)->after('expiration_date');
            $table->date('inventory_date')->nullable()->after('boxes');
            $table->string('image_url')->nullable()->after('inventory_date');
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'barcode', 'lot', 'rotation', 'production_date',
                'expiration_date', 'boxes', 'inventory_date', 'image_url'
            ]);
        });
    }
};