<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_raw_materials', function (Blueprint $table) {
            $table->foreignId('product_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('raw_material_id')->after('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 3); // cantidad necesaria por unidad producida
        });
    }

    public function down(): void
    {
        Schema::table('product_raw_materials', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['raw_material_id']);
            $table->dropColumn(['product_id', 'raw_material_id', 'quantity']);
        });
    }
};