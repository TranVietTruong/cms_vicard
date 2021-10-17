<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use function PHPSTORM_META\type;

class GoogleRecaptchaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        $googleRecaptchaSecret = env('GOOGLE_RECAPTCHA_SECRET');
        $googleRecaptchaSecret = '6Lfy2p8bAAAAAJcW7rJ_6afj8EK87DtFoJWBDMKL';
//        $googleRecaptchaApi = 'https://www.google.com/recaptcha/api/siteverify?secret='.$googleRecaptchaSecret.'&response='.$value. "&remoteip=" . rawurlencode($_SERVER['REMOTE_ADDR']);
        $googleRecaptchaApi = 'https://www.google.com/recaptcha/api/siteverify';
        $response = Http::asForm()->post($googleRecaptchaApi, [
            'secret' => $googleRecaptchaSecret,
            'response' => $value
        ])->body();
        return json_decode($response)->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Captcha không hợp lệ';
    }
}
