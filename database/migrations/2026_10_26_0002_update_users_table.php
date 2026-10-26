<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('branch_id')->nullable()->after('id');
            $table->string('nik')->unique()->nullable()->after('branch_id');
            $table->string('nama')->nullable()->after('nik');
            $table->string('jabatan')->nullable()->after('nama');
            $table->string('foto_url')->nullable()->after('jabatan');
            $table->boolean('face_registered')->default(false)->after('foto_url');
            $table->text('face_embedding')->nullable()->after('face_registered');
            $table->string('device_id')->nullable()->after('face_embedding');
            $table->boolean('is_active')->default(true)->after('device_id');

            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn([
                'branch_id',
                'nik',
                'nama',
                'jabatan',
                'foto_url',
                'face_registered',
                'face_embedding',
                'device_id',
                'is_active',
            ]);
        });
    }
};
