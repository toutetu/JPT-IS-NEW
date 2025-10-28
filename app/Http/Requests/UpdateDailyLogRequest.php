<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isStudent() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'target_date' => ['required', 'date'],
            'health_score' => ['required', 'integer', 'between:1,5'],
            'mental_score' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'max:4000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'target_date.required' => '対象日は必須です。',
            'target_date.date' => '有効な日付を入力してください。',
            'health_score.required' => '体調スコアは必須です。',
            'health_score.integer' => '体調スコアは整数で入力してください。',
            'health_score.between' => '体調スコアは1〜5の範囲で入力してください。',
            'mental_score.required' => 'メンタルスコアは必須です。',
            'mental_score.integer' => 'メンタルスコアは整数で入力してください。',
            'mental_score.between' => 'メンタルスコアは1〜5の範囲で入力してください。',
            'body.required' => '内容は必須です。',
            'body.max' => '内容は4000文字以内で入力してください。',
        ];
    }
}
