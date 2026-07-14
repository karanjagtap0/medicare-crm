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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')
                ->constrained();

            $table->foreignId('medicine_batch_id')
                ->constrained();

            $table->enum('transaction_type',[
                'Stock In',
                'Stock Out',
                'Adjustment',
                'Transfer',
                'Return'
            ]);

            $table->integer('quantity');

            $table->integer('previous_stock');

            $table->integer('current_stock');

            $table->string('reference_no')->nullable();

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
