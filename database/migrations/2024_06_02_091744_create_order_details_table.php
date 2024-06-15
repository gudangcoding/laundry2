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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orders_id')->constrained('orders');
            $table->foreignId('produks_id')->constrained('produks');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { Schema::table('order_details', function (Blueprint $table) {
        $table->dropSoftDeletes(); // This will remove the 'deleted_at' column
    });
        Schema::dropIfExists('order_details');
    }
};
