<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('fields')->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained('time_slots')->restrictOnDelete();
            $table->date('booking_date');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['field_id', 'booking_date']);
        });

        if (in_array(DB::getDriverName(), ['pgsql', 'sqlite'])) {
            DB::statement("CREATE UNIQUE INDEX bookings_no_overlap_active_unique ON bookings (field_id, booking_date, time_slot_id) WHERE status IN ('pending', 'confirmed')");
        } else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('active_booking_key')
                    ->nullable()
                    ->virtualAs("CASE WHEN status IN ('pending', 'confirmed') THEN CONCAT(field_id, '-', booking_date, '-', time_slot_id) ELSE NULL END");
                $table->unique('active_booking_key', 'bookings_no_overlap_active_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
