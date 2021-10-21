<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FicOrcamentariaCPRequest extends FormRequest
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
    public function rules()    {
        //dd($this->request); 
        return [
            "conta_id" => ['required','array'],
            "conta_id.*" => ['required']
        ];
    }
}