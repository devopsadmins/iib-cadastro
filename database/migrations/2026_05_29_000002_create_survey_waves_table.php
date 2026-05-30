<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_waves', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('year');
            $table->tinyInteger('wave');
            $table->string('label', 80)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['year', 'wave']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_waves');
    }
};
