<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use App\Models\Fazenda;
use Illuminate\Support\Str;


class EstoqueValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $origem;
    protected $message;

    public function __construct(String $origem)
    {
        if($origem){
            $this->origem = Fazenda::where('id', $origem)->first();
        }
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
        if(!$this->origem) return true;

        if($attribute == 'qtd_macho'){
            if($this->origem->qtd_macho < $value){
                $this->message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda('.$this->origem->qtd_macho.') - movimentação('.$value.')';
                return false;
            } else {
                return true;
            }
        }

        if($attribute == 'qtd_femea'){
            if($this->origem->qtd_femea < $value){
                $this->message = 'A quantidade FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda('.$this->origem->qtd_femea.') - movimentação('.$value.')';
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */

    public function message()
    {
        return $this->message;
    }

}
