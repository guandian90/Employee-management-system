<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionnaire_id'); // 外键
            $table->string('question_text'); // 问题内容
            $table->enum('type', ['single_choice', 'multiple_choice', 'short_answer']); // 题型
            $table->json('options')->nullable(); // 选项（如 ["选项1", "选项2"]）
            $table->text('description')->nullable(); // 问题描述
            $table->timestamps();

            // 外键约束
            $table->foreign('questionnaire_id')
                ->references('id')->on('questionnaires')
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
        Schema::dropIfExists('questions');
    }
}
