<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['cash', 'bank_transfer', 'momo', 'vnpay'])->default('cash');
            
            // 🎯 ĐÃ SỬA: Mở rộng tập hợp enum để chấp nhận trạng thái 'pending' từ seeder ma trận dữ liệu mẫu
            $table->enum('status', ['unpaid', 'pending', 'paid', 'refunded', 'completed', 'success'])->default('unpaid');
            
            $table->string('transaction_code')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['method', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};