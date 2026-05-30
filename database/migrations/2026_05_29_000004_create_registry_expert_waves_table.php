<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registry_expert_waves', function (Blueprint $table) {
            $table->foreignId('expert_id')->constrained('registry_experts')->cascadeOnDelete();
            $table->foreignId('wave_id')->constrained('survey_waves')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['expert_id', 'wave_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registry_expert_waves');
    }
};
