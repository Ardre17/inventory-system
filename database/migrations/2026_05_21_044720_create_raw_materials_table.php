<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->string('unit')->after('description');
            $table->decimal('stock', 10, 3)->default(0)->after('unit');
            $table->decimal('min_stock', 10, 3)->default(0)->after('stock');
            $table->decimal('cost', 10, 2)->default(0)->after('min_stock');
            $table->foreignId('supplier_id')->nullable()->after('cost')->constrained()->nullOnDelete();
            $table->string('lot')->nullable()->after('supplier_id');
            $table->date('expiration_date')->nullable()->after('lot');
            $table->string('image_url')->nullable()->after('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['name','description','unit','stock','min_stock','cost','supplier_id','lot','expiration_date','image_url']);
        });
    }
};