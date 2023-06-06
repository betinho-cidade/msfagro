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
    protected $data_programada;

    public function __construct(String $data_programada)
    {
        $this->data_programada = $data_programada;
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
        $today = Carbon::today();

        if($this->data_programada > $today){
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
        return 'Comprovante de pagamento somente é permitido para a Data Programada sendo igual ou anterior a data atual';
    }

}
