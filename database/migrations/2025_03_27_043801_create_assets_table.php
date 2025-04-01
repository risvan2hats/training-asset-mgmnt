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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no')->unique();
            $table->string('asset_type');
            $table->string('hardware_standard');
            $table->foreignId('location_id')->constrained();
            $table->decimal('asset_value', 10, 2);
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->string('country_code');
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
