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
        Schema::create('employee_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->onDelete('cascade');
            // $table->unsignedBigInteger('department_id');
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('department_id')->constrained('departments')->nullable()->onDelete('cascade');
            $table->string('phone');
            $table->date('joining_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_data');
    }
};
