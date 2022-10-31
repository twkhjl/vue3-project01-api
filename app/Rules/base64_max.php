<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class base64_max implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $max_size;
    public function __construct($max_size)
    {
        $this->max_size=$max_size;

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
            $res = getBase64FileSize($value);
            if ($res <= $this->max_size) {
                return true;
            }
            return false;

        }catch(\Exception $e){
            return $e;

        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute圖片最大為{$this->max_size}MB";
    }
}

// https://stackoverflow.com/questions/12658661/validating-base64-encoded-images
function getBase64FileSize($base64File){ //return memory size in B, KB, MB
    try{
        $size_in_bytes = (int) (strlen(rtrim($base64File, '=')) * 3 / 4);
        $size_in_kb    = $size_in_bytes / 1024;
        $size_in_mb    = $size_in_kb / 1024;

        return $size_in_mb;
    }
    catch(\Exception $e){
        return $e;
    }
}
