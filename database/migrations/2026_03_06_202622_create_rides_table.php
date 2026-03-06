<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('km', 8, 2);
            $table->decimal('deadhead_km', 8, 2)->default(3);
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->decimal('fare', 10, 2);
            $table->decimal('mcd_toll', 8, 2)->default(0);
            $table->decimal('paid_toll', 8, 2)->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
