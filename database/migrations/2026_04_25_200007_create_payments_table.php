<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
                $table->decimal('amount', 10, 2);
                $table->enum('method', ['cash', 'bank_transfer', 'moyasar', 'check']);
                $table->string('transaction_id')->nullable();
                $table->string('reference')->nullable();
                $table->foreignId('paid_by')->constrained('users')->cascadeOnDelete();
                $table->string('receipt_pdf')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
