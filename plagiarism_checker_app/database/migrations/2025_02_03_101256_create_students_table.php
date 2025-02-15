<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students')) {
            return;
        }

        Schema::create('students', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('student_code', 20)->unique();
            $table->date('enrollment_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('student_code');
            $table->index('enrollment_date');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        Schema::dropIfExists('students');
    }
};
