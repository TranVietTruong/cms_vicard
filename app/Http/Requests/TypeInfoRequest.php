<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeInfoRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|max:10'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Có lỗi xảy ra',
            'type.max' => 'Có lỗi xảy ra',
        ];
    }
}
