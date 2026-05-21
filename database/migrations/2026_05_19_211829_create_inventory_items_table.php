<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_period_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('initial_stock')->default(0);
            $table->integer('entries')->default(0);
            $table->integer('exits')->default(0);
            $table->integer('final_stock')->default(0);
            $table->integer('physical_count')->nullable();
            $table->integer('difference')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory_items');
    }
};