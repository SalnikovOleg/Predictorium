<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outcome_history', function (Blueprint $table) {
            $table->foreignId('outcome_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at');
            $table->enum('result', ['win', 'lose', 'return']);
            $table->primary(['outcome_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outcome_history');
    }
};
