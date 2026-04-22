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
        // Teachers
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('education_level_id')->nullable()->after('user_id')->constrained('education_levels')->onDelete('set null');
        });

        // Classrooms
        Schema::table('classrooms', function (Blueprint $table) {
            $table->foreignId('education_level_id')->nullable()->after('id')->constrained('education_levels')->onDelete('cascade');
        });

        // Curriculums
        Schema::table('curriculums', function (Blueprint $table) {
            $table->foreignId('education_level_id')->nullable()->after('id')->constrained('education_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['education_level_id']);
            $table->dropColumn('education_level_id');
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['education_level_id']);
            $table->dropColumn('education_level_id');
        });

        Schema::table('curriculums', function (Blueprint $table) {
            $table->dropForeign(['education_level_id']);
            $table->dropColumn('education_level_id');
        });
    }
};
