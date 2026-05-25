<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Agregar número de orden del cliente y código de barras a orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('client_order_number')->nullable()->after('order_type');
            $table->string('barcode')->nullable()->after('client_order_number');
        });

        // Agregar cantidad enviada a order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('quantity_sent')->default(0)->after('quantity');
            $table->enum('dispatch_status', ['pending', 'partial', 'complete', 'none'])
                  ->default('pending')->after('quantity_sent');
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['client_order_number', 'barcode']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['quantity_sent', 'dispatch_status']);
        });
    }
};
