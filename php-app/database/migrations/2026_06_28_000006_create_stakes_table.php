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
            $table->bigInteger('market_id');
            $table->foreignId('outcome_id');
            $table->json('outcome_ids')->nullable();
            $table->double('sum_in')->nullable();
            $table->double('coef')->nullable();
            $table->enum('result', ['win', 'lose', 'return'])->nullable();
            $table->double('sum_out')->nullable();
            $table->timestamps();

            $table->index(['group_id', 'user_id', 'event_id']);
            $table->index(['market_id']);
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('stakes');
    }
};
