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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code',20)->unique();

            $table->string('first_name');
            $table->string('last_name')->nullable();

            $table->string('email')->unique()->nullable();

            $table->string('mobile',15)->unique();

            $table->date('dob')->nullable();

            $table->enum('gender',[
                'Male',
                'Female',
                'Other'
            ])->nullable();

            $table->boolean('status')->default(true);

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
