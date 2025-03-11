<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStepProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_step_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('step_id');
            $table->decimal('progress_percent', 5, 2)->nullable(); // 视频进度百分比（如：75.5）
            $table->timestamp('completed_at')->nullable(); // 完成时间
            // 唯一联合索引：一个用户对一个步骤只能有一条记录
            $table->unique(['user_id', 'step_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('step_id')->references('id')->on('steps')->cascadeOnDelete();
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
        Schema::dropIfExists('user_step_progress');
    }
}
