<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitQuestionnaireRequest;
use App\Models\UserAnswer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\Step;
use Illuminate\Support\Facades\Auth;
use App\Models\UserStepProgress;
use Illuminate\Support\Facades\Validator;

class QuestionnaireController extends Controller
{
    public function submit(SubmitQuestionnaireRequest $request, $questionnaireId)
    {
        $validated = $request->validated();
        $userId = Auth::id();
        $step = null; // 声明在事务外

        DB::transaction(function () use ($validated, $userId, $questionnaireId, &$step) { // 添加 &step 引用
            // 批量插入答案
            $records = [];
            foreach ($validated['answers'] as $questionId => $answer) {
                $records[] = [
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'answer' => is_array($answer) ? json_encode($answer) : $answer,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            UserAnswer::insert($records);

            // 触发步骤完成
            $step = \App\Models\Questionnaire::find($questionnaireId)->step;
            app(StepController::class)->complete(request(), $step);
        });

        // 验证 $step 是否有效
        if (!$step) {
            return response()->json(['error' => '问卷步骤未找到'], 500);
        }

        return redirect()
            ->route('step.show', ['step' => $step->id])
            ->with('success', '问卷提交成功');
    }

    // app/Http/Controllers/QuestionnaireController.php
    public function store(Request $request, Step $step)
    {
        $validated = request('answers');
        $userId = Auth::id();
        $questionnaireId = $step->questionnaire_id;

        $rules = [];
        $messages = [];

        $questionnaire = Step::find($step->id)->questionnaire;

        // 检查问卷是否存在
        if (!$questionnaire) {
            return redirect()->back()->withErrors(['error' => '问卷未找到'])->withInput();
        }

        $data = request('answers');
        $errors = [];

        foreach ($questionnaire->questions as $question) {
            $answer = $data[$question->id] ?? null;

            if ($question->type === 'single_choice') {
                if (empty($answer) || !is_string($answer)) {
                    $errors["answers.{$question->id}"] = "第 {$question->id} 题是必填项";
                }
            } elseif ($question->type === 'multiple_choice') {
                if (empty($answer) || !is_array($answer) || count($answer) < 2) {
                    $errors["answers.{$question->id}"] = "第 {$question->id} 题至少需要选择两个选项";
                }
            } elseif ($question->type === 'short_answer') {
                if (empty($answer) || !is_string($answer)) {
                    $errors["answers.{$question->id}"] = "第 {$question->id} 题是必填项";
            }
        }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try {
            // 批量插入答案
            $records = [];
            $answer_str = "";
            foreach ($validated as $questionId => $answer) {
                $answer_str = is_array($answer) ? json_encode($answer) : $answer;
                $records[] = [
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'answer' => $answer_str,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
            UserAnswer::insert($records);

            // 触发步骤完成
            app(StepController::class)->complete(request(), $step);

            // 保存用户问卷进度
            UserStepProgress::updateOrCreate(
                ['user_id' => $userId, 'step_id' => $step->id],
                ['completed_at' => now(), 'answers' => $answer_str]
            );

            // 更新用户当前步骤
            \App\Models\User::where('id', $userId)->update(['current_step_id' => $step->id]);

            return redirect()
                ->route('home')
                ->with('success', '问卷提交成功');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => '问卷提交失败，请重试']);
        }
    }

}
