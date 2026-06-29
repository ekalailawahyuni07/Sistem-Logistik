<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cluster', function (Blueprint $table) {
            $table->string('kode_cluster', 50)->nullable()->after('id_cluster');
        });
    }

    public function down(): void
    {
        Schema::table('cluster', function (Blueprint $table) {
            $table->dropColumn('kode_cluster');
        });
    }
};