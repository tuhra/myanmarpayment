<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MsisdnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile_number' => 'required|numeric|digits_between:9,11'
        ];
    }
    
    public function messages() {
       return [
            'mobile_number.required' => 'Enter your number',
            'mobile_number.digits_between' => 'Enter your valid number',
        ];
    }
}
