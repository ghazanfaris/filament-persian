<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iran_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('province_id')->index();
            $table->string('name', 100);
            $table->string('name_en', 100)->nullable();
            $table->string('slug', 100)->nullable()->index();
            $table->unsignedInteger('population')->nullable();
            $table->boolean('is_capital')->default(false);
            $table->timestamps();

            $table->index(['province_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iran_cities');
    }
};