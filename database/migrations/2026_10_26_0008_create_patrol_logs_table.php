<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrol_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('user_id');
            $table->uuid('checkpoint_id');
            $table->timestamp('timestamp_server')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('foto_url')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('checkpoint_id')->references('id')->on('patrol_checkpoints')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_logs');
    }
};
