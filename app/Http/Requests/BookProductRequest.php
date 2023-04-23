<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                'exists:clients,id',
                Rule::unique('bookings')->where('product_id', request('product_id'))
            ],
            'product_id' => [
                'required',
                'exists:products,id',
                Rule::unique('bookings')->where('client_id', request('client_id'))
            ],
            'booked_on' => 'required|date'
        ];
    }
}
