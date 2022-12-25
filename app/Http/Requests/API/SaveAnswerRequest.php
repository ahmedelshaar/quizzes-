<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'quiz_schedules_id' => 'required|exists:quiz_sessions,quiz_schedules_id',
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.answer' => 'required'
        ];
    }
}
