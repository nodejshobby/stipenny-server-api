<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreStipendRequest extends FormRequest
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
            'title' => ['required','regex:/^[a-z][a-z ]+[a-z]$/i', 'min:3'],
            'amount' => ['required','regex:/^[1-9][0-9]+[0-9]$/'],
            'interval' => ['required','in:daily,weekly,monthly'],
            'limit' => 'required|integer'
        ];
    }
}
