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
        Schema::table('users', function (Blueprint $table) {

            $table->string('foto_profile')->nullable()->after('password');

            $table->string('no_telp',20)->nullable()->after('foto_profile');

            $table->text('alamat')->nullable()->after('no_telp');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'foto_profile',
                'no_telp',
                'alamat'
            ]);

        });
    }
};
