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
        Schema::create('users', static function (Blueprint $table): void {
            $table->id();
            $table->string('email', 191)->unique();
            $table->string('password', 255);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('full_name', 100);
            $table->date('dob');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('is_admin')->default(false);
            $table->timestamp('last_login_at')->nullable()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('profile_updated_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
