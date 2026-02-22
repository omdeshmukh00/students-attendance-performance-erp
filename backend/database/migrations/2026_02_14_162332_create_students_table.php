<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {

            $table->id();

            $table->string('student_id')->unique();
            $table->string('name');

            $table->string('branch');
            $table->string('section');
            $table->integer('semester');

            $table->integer('overall_total')->nullable();
            $table->integer('overall_attended')->nullable();
            $table->float('overall_percentage')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};