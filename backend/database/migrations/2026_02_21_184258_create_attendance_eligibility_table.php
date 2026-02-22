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
        Schema::create('attendance_eligibility', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->integer('attendance_percentage');

            $table->boolean('eligible_for_mse1');
            $table->boolean('eligible_for_mse2');
            $table->boolean('eligible_for_endsem');
            $table->boolean('incentive_eligible'); // 80%+

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_eligibility');
    }
};
