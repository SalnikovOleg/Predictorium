<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stake_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stake_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('market_id');
            $table->bigInteger('outcome_id');
            $table->double('coef')->nullable();
            $table->enum('result', ['win', 'lose', 'return'])->nullable();
            $table->timestamps();

            $table->index(['stake_id']);
            $table->index(['market_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stake_items');
    }
};
