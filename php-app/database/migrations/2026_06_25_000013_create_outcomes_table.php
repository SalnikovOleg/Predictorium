<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->integer('outcome_type_id');
            $table->integer('result_type_id');
            $table->foreignId('participant_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('coef', 5, 2);
            $table->enum('result', ['win', 'lose', 'return'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outcomes');
    }
};
