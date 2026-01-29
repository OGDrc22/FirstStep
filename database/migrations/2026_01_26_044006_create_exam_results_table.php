<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();

            // 🔑 link to student
            $table->foreignId('student_id')
                ->constrained('student_tb')
                ->onDelete('cascade');

            $table->string('predicted_track');
            $table->json('track_percentage');
            $table->float('accuracy');

            $table->json('answers');
            $table->json('questions');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
