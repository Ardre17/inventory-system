<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('production_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('sticker', [
                'no_usa',
                '30mm', '43mm', '50mm', '55mm',
                '65mm', '70mm', '85mm'
            ])->default('no_usa');
            $table->enum('precinto', [
                'no_usa',
                '74x30', '94x30', '97x30', '106x30',
                '118x30', '128x30', '138x30', '175x30',
                'aliño_2lt', 'aliños_pequeños'
            ])->default('no_usa');
            $table->enum('label_type', ['local', 'ingles', 'portugues'])->default('local');
            $table->enum('sticker_idioma', ['español', 'portugues', 'no_usa'])->default('no_usa');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('production_order_items');
    }
};