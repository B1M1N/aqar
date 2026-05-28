<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
                $table->string('unit_number');
                $table->enum('type', ['apartment', 'studio', 'room', 'floor', 'shop', 'suite']);
                $table->unsignedSmallInteger('floor')->default(1);
                $table->decimal('area', 10, 2);
                $table->unsignedTinyInteger('bedrooms')->default(0);
                $table->unsignedTinyInteger('bathrooms')->default(0);
                $table->decimal('rent_price', 12, 2);
                $table->enum('rent_period', ['monthly', 'quarterly', 'yearly'])->default('monthly');
                $table->enum('status', ['available', 'occupied', 'reserved', 'maintenance'])->default('available');
                $table->json('features')->nullable();
                $table->json('images')->nullable();
                $table->text('notes')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
