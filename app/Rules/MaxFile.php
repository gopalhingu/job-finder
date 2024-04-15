<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxFile implements Rule
{
    public $length = 10;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($length)
    {
        $this->length = $length;
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
        $size = $value->getSize() / 1024;
        if ($this->length >= $size) {
            return true;
        }        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.max_file', array('max' => $this->length));
    }
}
