<?php

use App\Enums\DocumentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('media_path_id')->nullable();
            $table->string('status')->default(DocumentStatus::PENDING->value);
            $table->json('metadata')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_batches');
    }
};
