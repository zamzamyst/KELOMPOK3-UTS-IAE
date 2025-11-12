<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->unsignedBigInteger('schedule_id');
            $table->string('passenger_name');
            $table->string('passenger_contact')->nullable();
            $table->integer('seat_count')->default(1);
            $table->decimal('total_price',10,2)->default(0);
            $table->string('status')->default('pending'); // pending, confirmed, paid, cancelled
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tickets'); }
};
