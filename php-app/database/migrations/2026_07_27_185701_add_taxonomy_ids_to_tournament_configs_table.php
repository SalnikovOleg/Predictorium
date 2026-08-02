<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("tournament_configs", function (Blueprint $table) {
            $table->json("taxonomy_ids")->nullable()->after("market_template_ids");
        });
    }

    public function down(): void
    {
        Schema::table("tournament_configs", function (Blueprint $table) {
            $table->dropColumn("taxonomy_ids");
        });
    }
};