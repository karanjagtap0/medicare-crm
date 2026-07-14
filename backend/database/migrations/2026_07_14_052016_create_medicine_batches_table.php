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
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('batch_number',50)->unique();

            $table->date('manufacturing_date')->nullable();

            $table->date('expiry_date');

            $table->decimal('purchase_price',10,2);

            $table->decimal('selling_price',10,2);

            $table->integer('quantity_received');

            $table->integer('available_quantity');

            $table->string('barcode')->nullable();

            $table->enum('status',[
                'Active',
                'Expired',
                'Blocked'
            ])->default('Active');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
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
        Schema::dropIfExists('medicine_batches');
    }
};
