<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained('sports')->restrictOnDelete();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->decimal('price_per_hour', 12, 2);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_id', 'sport_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
