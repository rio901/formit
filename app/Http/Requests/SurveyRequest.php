<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invite_code' => 'required|max:32|unique:create_format', 
            'name' => 'required|string|max:255',
            'text' => 'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'invite_code.required' => '招待コードは必須項目です。',
            'invite_code.max' => '招待コードは:max文字以内で入力してください。',
            'invite_code.unique' => 'その招待コードは既に使用されています。',
            'name.required' => 'アンケート名は必須項目です。',
            'name.max' => 'アンケート名は:max文字以内で入力してください。',
            'text.max' => 'max文字以内で入力してください。',
        ];
    }
}
