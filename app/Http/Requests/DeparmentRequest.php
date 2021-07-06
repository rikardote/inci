<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DeparmentRequest extends Request
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
            'code' => 'min:2|required|unique:deparments',
            'description' => 'min:4|required',
        ];
    }
}
