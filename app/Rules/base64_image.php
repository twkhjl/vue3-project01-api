<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class base64_image implements Rule
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
        try{
            $res = mime_content_type($value);
            if ($res == 'image/png' || $res == 'image/jpeg') {
                return true;
            }

        }catch(\Exception $e){
            return false;

        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute格式限制:jpg,png';
    }
}
