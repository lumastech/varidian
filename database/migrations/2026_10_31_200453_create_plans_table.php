<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('audience')->nullable();
            $table->string('hestia_package')->unique();
            $table->unsignedInteger('price_ngwee');
            $table->unsignedInteger('storage_gb');
            $table->unsignedInteger('bandwidth_gb');
            $table->unsignedInteger('websites');
            $table->unsignedInteger('databases');
            $table->unsignedInteger('cron_jobs');
            $table->unsignedInteger('backups');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
