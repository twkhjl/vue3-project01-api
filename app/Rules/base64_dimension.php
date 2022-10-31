<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class base64_dimension implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $width;
    private $height;
    public function __construct($width,$height)
    {
        $this->width=$width;
        $this->height=$height;

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

            // https://stackoverflow.com/questions/48828055/how-to-get-height-and-width-from-base64-encoded-image
            $binary = \base64_decode(\explode(',', $value)[1]);

            $data = \getimagesizefromstring($binary);
            $data = $data['3'];
            $data = str_replace('"','',$data);
            $data = str_replace(' ',',',$data);

            // https://stackoverflow.com/questions/4923951/php-split-string-in-key-value-pairs
            preg_match_all("/([^,= ]+)=([^,= ]+)/", $data, $r);
            $data = array_combine($r[1], $r[2]);

            $width=$data['width']*1;
            $height=$data['height']*1;


            if ($width <= $this->width && $height <= $this->height) {
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
        return ":attribute圖片最大尺寸為{$this->width}X{$this->height}";
    }
}


