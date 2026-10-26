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
        Schema::table('program_details', function (Blueprint $table) {
            if (!Schema::hasColumn('program_details', 'category')) {
                $table->string('category', 100)->nullable()->after('program_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_details', function (Blueprint $table) {
            if (Schema::hasColumn('program_details', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
