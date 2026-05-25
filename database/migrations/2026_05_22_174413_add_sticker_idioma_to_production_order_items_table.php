<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('production_order_items', function (Blueprint $table) {
            $table->string('sticker_idioma')->default('no_usa')->after('label_type');
        });
    }
    public function down(): void {
        Schema::table('production_order_items', function (Blueprint $table) {
            $table->dropColumn('sticker_idioma');
        });
    }
};
