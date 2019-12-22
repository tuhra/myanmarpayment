<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpRequest extends FormRequest
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
            'pin' => 'required|digits:4'
        ];
    }
    
    public function messages() {
        return [
            'pin.required' => 'Enter your 4 digit pin',
            'pin.digits' => 'Enter your 4 digit pin',            
        ];
    }
}
