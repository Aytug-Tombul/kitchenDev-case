<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code'=>'required',
            'description'=>'required',
            'cost'=>'required|numeric|min:0',
            'quantity_available'=>'required|numeric|min:0'
        ];
    }


    public function messages()
{
    return [
        'code.required' => 'A code is required',
        'description.required' => 'A description is required',
        'cost.required' => 'A cost is required',
        'quantity_available.required' => 'A quantity_available is required',
        'cost.numeric'=>'A cost not-numeric',
        'quantity_available.numeric'=>'A quantity_available not-numeric',
        'cost.min' =>'cost cannot be lower than zero',
        'quantity_available.min' =>'quantity_available cannot be lower than zero',
    ];
}
}
