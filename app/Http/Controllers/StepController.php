<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Step;
use App\Models\UserStepProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepController extends Controller
{
    protected $complete_id = 3;

    public function currentStep()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->intended(route('login'));
        }
        $currentStepId = $user->current_step_id;
        if ($currentStepId == $this->complete_id) {
            return view('steps.complete');
        }
        $currentStepId += 1;
        $currentStep = $currentStepId
            ? Step::with('questionnaire')->find($currentStepId)
            : Step::with('questionnaire')->first();
        if (!$currentStep) {
            abort(404, '未找到初始步骤，请检查步骤数据');
        }
        return view('steps.current', compact('currentStep'));
    }

    public function show(Step $step)
    {
        $user = Auth::user();

        // 检查用户是否已经完成了问卷
        $userStepProgress = UserStepProgress::where([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ])->first();

        // 如果 $step 为空，则根据当前用户的 current_step_id 查询步骤
        if (!$step) {
            $currentStepId = $user->current_step_id;
            $step = Step::with('questionnaire.questions', 'userStepProgress')->find($currentStepId);

            // 如果仍然找不到步骤，返回 404 错误
            if (!$step) {
                abort(404, '未找到当前步骤，请检查步骤数据');
            }
        } else {
            // 确保加载关联数据
            $step->load(['questionnaire.questions', 'userStepProgress']);
        }
        if ($userStepProgress && $userStepProgress->completed_at) {
            return view('questionnaires.show', [
                'data' => null,
                'step' => $step,
                'questionnaire' => $step->questionnaire,
                'isCompleted' => true,
            ]);
        }

        // 构建与原 JSON 结构一致的数据
        $responseData = [
            'step' => $step,
            'questionnaire' => [
                'title' => $step->questionnaire->title,
                'questions' => $step->questionnaire->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'text' => $question->question_text,
                        'type' => $question->type,
                        'options' => $question->options ?? [],
                        'description' => $question->description,
                    ];
                })
            ]
        ];
        // 将数据传递到视图
        return view('questionnaires.show', [
            'data' => $responseData,
            'step' => $step,
            'questionnaire' => $step->questionnaire,
            'isCompleted' => false,
        ]);
    }


    public function complete(Request $request, Step $step)
    {
        $user = Auth::user();
        if ($step->id != $user->current_step_id) {
            return response()->json(['error' => '步骤未解锁'], 400);
        }

        // 更新进度状态
        UserStepProgress::updateOrCreate(
            ['user_id' => $user->id, 'step_id' => $step->id],
            ['completed_at' => now()]
        );

        // 跳转到下一步（避免循环或阻塞）
        $nextStep = Step::where('order', '>', $step->order)
            ->orderBy('order')
            ->first();
        if (isset($nextStep->id)) {
            $user->update(['current_step_id' => $nextStep->id]);
        } else {
            // 如果没有下一步，可以设置为完成状态
            $user->update(['current_step_id' => $this->complete_id]);
        }

        return response()->json(['message' => '步骤完成']);
    }

    public function updateVideoProgress(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => '未登录'], 401);
        }

        $request->validate([
            'step_id' => 'required|exists:steps,id',
            'progress_percent' => 'required|numeric|min:0|max:100',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => '未登录'], 401);
        }
        UserStepProgress::updateOrCreate(
            ['user_id' => $user->id, 'step_id' => $request->step_id],
            ['progress_percent' => $request->progress_percent]
        );
        return response()->json(['success' => true]);
    }

    public function getProgress(Step $step)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => '未登录'], 401);
        }

        // 获取问卷进度（示例：假设问卷提交状态存储在 user_step_progress 表中）
        $isQuestionnaireCompleted = UserStepProgress::where([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ])
            ->whereNotNull('completed_at') // 判断 completed_at 不为空
            ->exists();

        // 获取视频进度
        $videoProgress = UserStepProgress::where([
            'user_id' => $user->id,
            'step_id' => $step->id
        ])->value('progress_percent') ?: 0;

        return response()->json([
            'questionnaire_completed' => $isQuestionnaireCompleted,
            'video_progress_percent' => $videoProgress,
        ]);
    }
}
