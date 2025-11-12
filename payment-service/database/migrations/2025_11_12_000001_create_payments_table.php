<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->decimal('amount',10,2);
            $table->string('status')->default('initiated'); // initiated, success, failed
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
