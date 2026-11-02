<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone', 20)->nullable();
            $table->text('shipping_address');
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'paid', 'processing', 'completed', 'cancelled'])
                  ->default('pending');
            $table->string('bank_name')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

