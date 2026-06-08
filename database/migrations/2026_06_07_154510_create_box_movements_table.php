<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('box_movements', function (Blueprint $table) {
        $table->id();

        $table->foreignId('box_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->enum('type', [
            'entrada',
            'salida'
        ]);

        $table->integer('quantity');

        $table->string('reason')->nullable();

        $table->text('observation')->nullable();

        $table->timestamps();
    });
}
};
