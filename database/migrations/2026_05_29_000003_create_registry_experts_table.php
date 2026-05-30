<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registry_experts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_type_id')->nullable()->constrained('expert_types')->nullOnDelete();
            $table->string('first_name', 120);
            $table->string('last_name', 120);
            $table->string('company', 255)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 120)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 255)->nullable()->unique();
            $table->foreignId('registration_wave_id')->nullable()->constrained('survey_waves')->nullOnDelete();
            $table->string('registration_wave_note', 80)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->index(['last_name', 'first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registry_experts');
    }
};
