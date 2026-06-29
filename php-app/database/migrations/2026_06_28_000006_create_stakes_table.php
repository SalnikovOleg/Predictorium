<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outcome_id')->constrained()->cascadeOnDelete();
            $table->double('sum_in');
            $table->double('coef');
            $table->enum('result', ['win', 'lose', 'return'])->nullable();
            $table->double('sum_out');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stakes');
    }
};
