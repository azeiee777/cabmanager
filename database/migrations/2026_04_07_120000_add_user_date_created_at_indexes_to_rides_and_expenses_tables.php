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
        Schema::table('rides', function (Blueprint $table) {
            $table->index(['user_id', 'date', 'created_at'], 'rides_user_date_created_at_idx');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['user_id', 'date', 'created_at'], 'expenses_user_date_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropIndex('rides_user_date_created_at_idx');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('expenses_user_date_created_at_idx');
        });
    }
};
