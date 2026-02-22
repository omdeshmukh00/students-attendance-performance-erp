<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_eligibilities', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();

            $table->integer('attendance_percentage');

            $table->boolean('eligible_mse1');
            $table->boolean('eligible_mse2');
            $table->boolean('eligible_endsem');
            $table->boolean('eligible_incentive');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_eligibilities');
    }
};