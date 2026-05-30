<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mailing_contact_waves', function (Blueprint $table) {
            $table->foreignId('mailing_contact_id')->constrained('mailing_contacts')->cascadeOnDelete();
            $table->foreignId('wave_id')->constrained('survey_waves')->cascadeOnDelete();
            $table->string('source_file')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['mailing_contact_id', 'wave_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mailing_contact_waves');
    }
};
