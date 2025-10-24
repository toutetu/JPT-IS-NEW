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
    Schema::create('homeroom_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
        $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnUpdate()->restrictOnDelete();
        $table->date('since_date')->nullable();
        $table->date('until_date')->nullable();
        $table->timestamps();

        $table->index(['teacher_id', 'classroom_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('homeroom_assignments');
}

};
