<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
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
            'first_name' => 'required|min:3|alpha',
            'last_name' => 'required|min:3|alpha',
            'email' => ['required', 'email', 'min:3', 'unique:users,email,' . $this->id],
            'username' => ['required', 'min:3', 'alpha_num', 'unique:users,username,' . $this->id],
            'mobile' => ['nullable', 'numeric', 'digits:11', 'unique:users,mobile,' . $this->id],
            'password' => 'nullable|confirmed|min:8',
            'user_group' => 'nullable|exists:user_groups,id'
        ];
    }
}
