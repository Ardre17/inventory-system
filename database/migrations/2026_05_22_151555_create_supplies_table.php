<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['sticker', 'precinto', 'etiqueta']);
            $table->string('variant');
            $table->integer('stock')->default(0);
            $table->integer('stock_min')->default(0);
            $table->integer('units_per_roll')->default(1000);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('supplies'); }
};
