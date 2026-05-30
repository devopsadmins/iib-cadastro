<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mailing_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('interviewee_name', 255);
            $table->string('company', 255)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('company_website', 500)->nullable();
            $table->string('merco_approval_status', 120)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->index('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mailing_contacts');
    }
};
