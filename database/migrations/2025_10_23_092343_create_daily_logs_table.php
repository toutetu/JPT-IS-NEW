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
    Schema::create('daily_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
        $table->date('target_date'); // 前登校日
        $table->tinyInteger('health_score'); // 1〜5
        $table->tinyInteger('mental_score'); // 1〜5
        $table->text('body'); // 本文
        $table->timestamp('submitted_at')->useCurrent();
        $table->timestamp('read_at')->nullable();
        $table->foreignId('read_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
        $table->timestamps();

        $table->unique(['student_id', 'target_date']);
    });
}

public function down(): void
{
    Schema::dropIfExists('daily_logs');
}

};
