<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('user_id');
            $table->uuid('location_id')->nullable();
            $table->uuid('shift_id')->nullable();
            $table->enum('type', ['masuk', 'pulang'])->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'tanpa_keterangan', 'pulang'])->nullable();
            $table->timestamp('timestamp_server')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('foto_url')->nullable();
            $table->double('face_match_score')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('shift_id')->references('id')->on('shifts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
