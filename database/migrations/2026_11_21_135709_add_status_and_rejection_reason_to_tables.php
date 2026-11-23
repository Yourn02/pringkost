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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'suspended', 'blocked'])->default('active')->after('phone');
            }
        });

        Schema::table('kosts', function (Blueprint $table) {
            if (!Schema::hasColumn('kosts', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('kosts', function (Blueprint $table) {
            if (Schema::hasColumn('kosts', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};
