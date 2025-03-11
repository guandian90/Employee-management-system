<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitQuestionnaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $questionIds = \App\Models\Question::where('questionnaire_id', $this->route('questionnaire'))->pluck('id');

        return [
            'answers' => [
                'required',
                'array',
                Rule::exists('questions', 'id')->whereIn('id', $questionIds)->forAll()
            ],
            'answers.*' => 'required|string'
        ];
    }
}
