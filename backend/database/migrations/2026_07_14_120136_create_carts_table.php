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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('sub_total',12,2)->default(0);

            $table->decimal('discount',12,2)->default(0);

            $table->decimal('tax',12,2)->default(0);

            $table->decimal('grand_total',12,2)->default(0);

            $table->enum('status',[
                'Active',
                'Converted',
                'Abandoned'
            ])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
