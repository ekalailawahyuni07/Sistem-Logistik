<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->foreignId('id_role')->constrained('roles', 'id_role')->onDelete('cascade');
            $table->foreignId('id_area')->constrained('area', 'id_area')->onDelete('cascade');
            $table->string('nama_user', 100);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status_validasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};