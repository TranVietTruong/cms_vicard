<?php

namespace App\Http\Requests;

use App\Rules\GoogleRecaptchaRule;

class RegisterRequest extends BaseRequest
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
            'full_name' => 'required|min:3|max:50',
            'email' => 'required|unique:users|email:rfc,dns|max:50',
            'password' => 'required|min:6|max:20',
            'code_tag' => 'required|min:6|max:10',
            'captcha' => ['required', new GoogleRecaptchaRule()]
        ];
    }

    public function messages()
    {
       return [
           'full_name.required' => 'Họ và tên không được để trống',
           'full_name.min' => 'Họ và tên phải tối thiểu 3 kí tự',
           'full_name.max' => 'Họ và tên tối đa 50 kí tự',
           'email.required' => 'Email không được để trống',
           'email.unique' => 'Email đã tồn tại',
           'email.email' => 'Email không hợp lệ',
           'email.max' => 'Email không hợp lệ',
           'password.required' => 'Password không được để trống',
           'password.min' => 'Password phải tối thiểu 6 kí tự',
           'password.max' => 'Password không hợp lệ',
           'code_tag.required' => 'Mã thẻ không được để trống',
           'code_tag.min' => 'Mã thẻ không hợp lệ',
           'code_tag.max' => 'Mã thẻ không hợp lệ'
       ];
    }
}
