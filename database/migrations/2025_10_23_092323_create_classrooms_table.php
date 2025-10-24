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
    Schema::create('classrooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grade_id')->constrained('grades')->cascadeOnUpdate()->restrictOnDelete();
        $table->string('name'); // 例: "A組", "B組"
        $table->timestamps();

        $table->index(['grade_id', 'name']); // 検索用
    });
}

public function down(): void
{
    Schema::dropIfExists('classrooms');
}

};
