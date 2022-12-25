<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizSessionQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_session_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('quiz_session_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('question_id');
            $table->text('question');
            $table->enum('type', ['MCQ Single Answer', 'MCQ Multi-Answer', 'Writing', 'True - False']);
            $table->json('options')->nullable();
            $table->json('correct_answer')->nullable();
            $table->text('hint')->nullable();
            $table->text('solution')->nullable();
            $table->text('image')->nullable();

            $table->json('answer')->nullable();
            $table->boolean('is_correct')->nullable();

            $table->primary(['quiz_session_id', 'question_id'], 'quiz_session_questions_primary');

            $table->foreign('quiz_session_id')
                ->references('id')
                ->on('quiz_sessions')
                ->onDelete('cascade');

            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_session_questions');
    }
}
