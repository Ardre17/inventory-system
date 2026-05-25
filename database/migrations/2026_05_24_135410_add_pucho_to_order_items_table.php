<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('pucho')->default(0)->after('pallet_number');
        });
    }
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('pucho');
        });
    }
};