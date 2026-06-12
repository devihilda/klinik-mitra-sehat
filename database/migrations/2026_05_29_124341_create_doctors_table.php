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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sip');
            $table->string('specialization');
            $table->foreignId('polyclinic_id')->constrained('polyclinics')->cascadeOnDelete();
            $table->string('phone');
            $table->string('status')->default('aktif'); // e.g. aktif, cuti, tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
