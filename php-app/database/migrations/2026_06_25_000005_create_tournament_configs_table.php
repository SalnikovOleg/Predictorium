<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('rules_json')->nullable();
            $table->json('market_template_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_configs');
    }
};
