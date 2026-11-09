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
        Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pencari kost
        $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade'); // Kost yang dipesan
        $table->date('start_date');
        $table->integer('duration_months')->default(1); // Durasi sewa dalam bulan
        $table->decimal('total_price', 12, 2);
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};