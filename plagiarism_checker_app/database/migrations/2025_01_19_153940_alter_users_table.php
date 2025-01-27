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
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'name')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->renameColumn('name', 'full_name');
                });
            }
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name', 50)->after('full_name');
                $table->string('last_name', 50)->after('first_name');
                $table->timestamp('last_login')->nullable()->after('email_verified_at');
                $table->timestamp('profile_updated')->nullable()->after('last_login');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'full_name')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->renameColumn('full_name', 'name');
                });
            }
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['first_name', 'last_name', 'last_login_timestamp', 'profile_updated']);
            });
        }
    }
};
