<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->string('pallet_number')->nullable();

            $table->integer('pucho')->default(0);

            $table->date('dispatch_expiration_date')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->dropColumn([
                'pallet_number',
                'pucho',
                'dispatch_expiration_date'
            ]);

        });
    }
};