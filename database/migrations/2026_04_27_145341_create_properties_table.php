<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->enum('type', ['building', 'apartment', 'villa', 'hotel']);
                $table->text('description')->nullable();
                $table->string('address');
                $table->string('city');
                $table->string('district')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->unsignedSmallInteger('floors')->default(1);
                $table->unsignedSmallInteger('build_year')->nullable();
                $table->enum('status', ['active', 'inactive', 'under_maintenance'])->default('active');
                $table->json('images')->nullable();
                $table->json('amenities')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
