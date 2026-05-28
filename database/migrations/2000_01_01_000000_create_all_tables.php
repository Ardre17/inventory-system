<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->nullable();
            $table->string('lot')->nullable();
            $table->enum('rotation', ['alta', 'media', 'baja'])->default('media');
            $table->date('production_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->decimal('boxes', 10, 2)->default(0);
            $table->integer('units_per_box')->default(1);
            $table->integer('stock_min')->default(5);
            $table->string('unit')->default('unidad');
            $table->date('inventory_date')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

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

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['sale', 'purchase'])->default('sale');
            $table->enum('order_type', ['local', 'encomienda', 'supermercado'])->default('local');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->string('client_supplier')->nullable();
            $table->string('client_order_number')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('quantity_sent')->default(0);
            $table->enum('dispatch_status', ['pending', 'partial', 'complete', 'none'])->default('pending');
            $table->string('pallet_number')->nullable();
            $table->integer('pucho')->default(0);
            $table->date('dispatch_expiration_date')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->default('kg');
            $table->decimal('stock', 10, 3)->default(0);
            $table->decimal('stock_min', 10, 3)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('lot')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_per_unit', 10, 4)->default(1);
            $table->decimal('quantity', 10, 4)->default(1);
            $table->decimal('stock', 10, 4)->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->enum('label_type', ['local', 'ingles', 'portugues'])->default('local');
            $table->string('sticker')->nullable();
            $table->string('precinto')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('production_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('sticker', ['no_usa','30mm','43mm','50mm','55mm','65mm','70mm','85mm'])->default('no_usa');
            $table->enum('precinto', ['no_usa','74x30','94x30','97x30','106x30','118x30','128x30','138x30','175x30','aliño_2lt','aliños_pequeños'])->default('no_usa');
            $table->enum('label_type', ['local','ingles','portugues'])->default('local');
            $table->enum('sticker_idioma', ['español','portugues','no_usa'])->default('no_usa');
            $table->timestamps();
        });

        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type');
            $table->string('variant')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('stock_min')->default(0);
            $table->integer('units_per_roll')->default(1000);
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('supply_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained()->onDelete('cascade');
            $table->enum('movement_type', ['entry', 'exit'])->default('entry');
            $table->integer('rolls')->default(0);
            $table->integer('quantity');
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('supply_movements');
        Schema::dropIfExists('supplies');
        Schema::dropIfExists('production_order_items');
        Schema::dropIfExists('production_orders');
        Schema::dropIfExists('product_raw_materials');
        Schema::dropIfExists('raw_materials');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_periods');
        Schema::dropIfExists('products');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
    }
};
