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
    Schema::table('students', function ($table) {
        $table->unique('student_id');
    });

    Schema::table('subjects', function ($table) {
        $table->unique('subject_code');
    });

    Schema::table('attendances', function ($table) {
        $table->unique(['student_id', 'subject_id']);
    });
}

public function down()
{
    Schema::table('students', function ($table) {
        $table->dropUnique(['student_id']);
    });

    Schema::table('subjects', function ($table) {
        $table->dropUnique(['subject_code']);
    });

    Schema::table('attendances', function ($table) {
        $table->dropUnique(['student_id', 'subject_id']);
    });
}
};
