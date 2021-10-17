<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserInfoRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|max:10',
            'key' => 'required|max:15',
            'info' => 'required|max:255',
            'info1' => 'max:255',
            'info2' => 'max:255',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Có lỗi xảy ra ! vui lòng thử lại sau',
            'type.max' => 'Có lỗi xảy ra ! vui lòng thử lại sau',
            'key.required' => 'Có lỗi xảy ra ! vui lòng thử lại sau',
            'key.max' => 'Có lỗi xảy ra ! vui lòng thử lại sau',
            'info.required' => 'Xin vui lòng điền đầy đủ thông tin',
            'info.max' => 'Có lỗi xảy ra ! vui lòng thử lại sau',
            'info1.required' => 'Xin vui lòng điền đầy đủ thông tin',
            'info1.max' => 'Có lỗi xảy ra ! vui lòng thử lại sau',
            'info2.required' => 'Xin vui lòng điền đầy đủ thông tin',
            'info2.max' => 'Có lỗi xảy ra ! vui lòng thử lại sau'
        ];
    }
}
