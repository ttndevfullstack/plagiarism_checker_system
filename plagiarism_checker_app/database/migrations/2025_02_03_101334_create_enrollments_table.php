<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('enrollments')) {
            return;
        }

        Schema::create('enrollments', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('students', 'id')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes', 'id')->cascadeOnDelete();
            $table->date('enrollment_date');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'class_id']);
            $table->index('enrollment_date');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('enrollments')) {
            return;
        }

        Schema::dropIfExists('enrollments');
    }
};
