<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;


class RangeValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $data_pagamento;

    public function __construct(String $data_pagamento)
    {
        $this->data_pagamento = $data_pagamento;
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
        if(!$this->data_pagamento) return true;
        
        $today = Carbon::today();

        if($this->data_pagamento > $today){
            return false;
        } else{
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
        return 'Comprovante de pagamento somente é permitido para a Data de Pagamento sendo igual ou anterior a data atual';
    }

}
