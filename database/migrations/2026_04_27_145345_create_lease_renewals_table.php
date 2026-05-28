<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('lease_renewals')) {
            Schema::create('lease_renewals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lease_id')->constrained('leases')->cascadeOnDelete();
                $table->date('old_end_date');
                $table->date('new_end_date');
                $table->decimal('new_rent_amount', 12, 2);
                $table->foreignId('renewed_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lease_renewals');
    }
};
