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
        Schema::create('cluster', function (Blueprint $table) {
            $table->id('id_cluster');
            $table->foreignId('id_area')->constrained('area', 'id_area')->onDelete('cascade');
            $table->string('nama_cluster', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cluster');
    }
};
