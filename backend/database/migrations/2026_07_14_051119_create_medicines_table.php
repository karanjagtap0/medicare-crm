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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_code',30)->unique();
            $table->string('sku',50)->unique();

            $table->string('name');
            $table->string('generic_name')->nullable();

            $table->text('description')->nullable();

            // Relations
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('brand_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('supplier_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('tax_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('unit_of_measure_id')->constrained()->cascadeOnUpdate();

            // Pricing
            $table->decimal('purchase_price',10,2);
            $table->decimal('selling_price',10,2);

            // Stock
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();

            // Product
            $table->boolean('prescription_required')->default(false);

            $table->boolean('status')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
