<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('teachers')) {
            return;
        }

        Schema::create('teachers', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('joined_date');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('joined_date');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('teachers')) {
            return;
        }

        Schema::dropIfExists('teachers');
    }
};