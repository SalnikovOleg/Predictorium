<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('market_type_id')->constrained()->cascadeOnDelete();
            $table->json('outcome_template_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_templates');
    }
};
