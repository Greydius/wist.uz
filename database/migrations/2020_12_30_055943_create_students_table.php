<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthdate');
            $table->date('visit_date')->nullable();
            $table->enum('application', ['unfilled', 'filled', 'filled-online'])->default('unfilled');
            $table->date('application_date')->nullable();
            $table->string('assessment')->default('');
            $table->date('assessment_date')->nullable();
            $table->enum('contract', ['ungiven', 'given', 'done', 'cancelled'])->default('ungiven');
            $table->enum('payment', ['unpaid', 'paid', 'paid-partly', 'cancelled'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
