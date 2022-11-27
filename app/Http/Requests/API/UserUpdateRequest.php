<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'id' => 'required|in:' . $user->id,
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'username' => ['required', 'min:3', 'alpha_num', Rule::unique('users')->ignore($user->id)],
            'mobile' => 'required|min:3',
            'email' => ['required', 'email', 'min:3', Rule::unique('users')->ignore($user->id)],
            'image' => 'image',
            'password' => 'confirmed|min:8'
        ];
    }
}
