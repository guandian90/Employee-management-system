<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Question;

class QuestionnaireStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 多选题验证
            'answers.*' => ['array', 'min:2'],

            // 单选题验证（可选）
            'answers' => ['required_without:answers.*'],

            // 短答案验证（可选）
            'answers.*' => ['nullable|string|max:200'],
        ];
    }

    public function messages()
    {
        return [
            'answers.*.required' => '问题必须回答',
            'answers.*.array' => '多选题格式错误',
            'answers.*.min' => '多选题需至少选择2个选项',
        ];
    }
}
