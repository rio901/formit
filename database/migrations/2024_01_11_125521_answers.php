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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->string('email');
            $table->unsignedBigInteger('question_id');
            $table->string('text_option');
            $table->string('free_description')->nullable();
            $table->timestamps();

            $table->foreign('response_id')
                  ->references('id')->on('response_answers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
