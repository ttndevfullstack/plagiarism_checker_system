<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('classes')) {
            return;
        }

        Schema::create('classes', static function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255)->unique();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('room_number', 20);
            $table->timestamps();
            $table->softDeletes();

            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('classes')) {
            return;
        }

        Schema::dropIfExists('classes');
    }
};
