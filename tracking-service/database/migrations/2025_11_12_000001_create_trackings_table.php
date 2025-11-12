<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id');
            $table->foreignId('schedule_id')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('trackings');
    }
};
