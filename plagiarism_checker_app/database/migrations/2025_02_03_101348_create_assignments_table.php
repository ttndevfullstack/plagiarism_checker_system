<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('assignments')) {
            return;
        }

        Schema::create('assignments', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('class_id')->constrained('classes', 'id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->date('assignment_date');
            $table->timestamps();
            $table->softDeletes();  

            $table->index('class_id');
            $table->index('teacher_id');
            $table->index('assignment_date');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('assignments')) {
            return;
        }

        Schema::dropIfExists('assignments');
    }
};
