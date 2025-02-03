<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'address')) {
            return;
        }

        Schema::table('users', static function (Blueprint $table): void {
            $table->string('avatar')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'avatar')) {
            return;
        }

        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn('avatar');
        });
    }
};
