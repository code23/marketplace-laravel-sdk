<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationForm extends FormRequest
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
            'email'                 => 'required|email',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'team_name'             => 'required',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
            'agree_terms'           => 'required',
        ];
    }
}
