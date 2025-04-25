<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plagiarism_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('document_id')->nullable()->constrained();
            $table->text('text_content')->nullable();
            $table->decimal('similarity_score', 5, 2);
            $table->unsignedTinyInteger('confidence_score');
            $table->json('matches');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plagiarism_checks');
    }
};
