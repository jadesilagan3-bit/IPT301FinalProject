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
        Schema::create('parking_sessions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
    $table->foreignId('parking_slot_id')->constrained()->restrictOnDelete();
    $table->dateTime('entry_time');
    $table->dateTime('exit_time')->nullable();
    $table->unsignedInteger('duration_minutes')->nullable();
    $table->string('status')->default('Active');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};
