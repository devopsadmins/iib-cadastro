<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_runs', function (Blueprint $table) {
            $table->id();
            $table->text('source_dir');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
            $table->text('notes')->nullable();
        });

        Schema::create('import_file_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained('import_runs')->cascadeOnDelete();
            $table->text('file_name');
            $table->string('category', 30);
            $table->string('type_slug', 80)->nullable();
            $table->smallInteger('year')->nullable();
            $table->tinyInteger('wave')->nullable();
            $table->integer('inserted_count')->default(0);
            $table->timestamp('processed_at')->useCurrent();

            $table->index('run_id');
            $table->index('category');
            $table->index(['year', 'wave']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_file_stats');
        Schema::dropIfExists('import_runs');
    }
};
