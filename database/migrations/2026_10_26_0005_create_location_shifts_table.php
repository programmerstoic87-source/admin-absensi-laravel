<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_shifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('location_id');
            $table->uuid('shift_id');
            $table->date('berlaku_dari')->nullable();
            $table->date('berlaku_sampai')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete();
            $table->foreign('shift_id')->references('id')->on('shifts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_shifts');
    }
};
