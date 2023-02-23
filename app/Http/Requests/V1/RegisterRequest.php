<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'firstname' => 'required|alpha|min:1',
            'lastname' => 'required|alpha|min:1',
            'email' => 'required|email|unique:users',
            'password' => ['required','confirmed',Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()]
        ];
    }
}
