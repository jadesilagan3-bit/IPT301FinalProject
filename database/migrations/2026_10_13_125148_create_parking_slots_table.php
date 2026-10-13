<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
       Schema::create('parking_slots', function (Blueprint $table) {
    $table->id();
    $table->string('slot_number')->unique();
    $table->string('slot_type');
    $table->string('status')->default('Available');
    $table->string('location')->nullable();
    $table->timestamps();
}); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
