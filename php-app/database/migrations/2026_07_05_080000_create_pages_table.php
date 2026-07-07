<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_pages', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->char('lang', 2);
            $table->string('title', 255);
            $table->longText('content');
            $table->timestamps();

            $table->index(['model_type', 'model_id', 'lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_pages');
    }
};
