<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plagiarism_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->nullable()->constrained();
            $table->foreignId('class_id')->nullable()->constrained();
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->decimal('originality_score', 5, 2)->unsigned()->between(0, 100);
            $table->decimal('similarity_score', 5, 2)->unsigned()->between(0, 100);
            $table->unsignedTinyInteger('source_matched');
            $table->unsignedInteger('words_analyzed');
            $table->json('encoded_file')->nullable();
            $table->json('results');
            $table->foreignId('uploaded_by')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plagiarism_histories');
    }
};
