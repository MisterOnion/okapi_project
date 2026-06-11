<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name'                 => ['required', 'string', 'max:255'],
            'email'                         => ['required', 'email', 'unique:leads,email'],
            'phone_number'                  => ['required', 'string', 'max:20'],
            'monthly_electricity_bill_rm'   => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max: 1500'],
            'property_type'                 => ['required', 'in:landed,condo,apartment,commercial'],
            'roof_type'                     => ['required', 'in:tile,metal,flat,concrete'],
            'state'                         => ['required', 'string']

        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email has already submitted a lead.',
            'property_type.in' => 'Property type must be landed, condo, apartment, or commercial.',
            'roof_type.in' => 'Roof type must be tile, metal, flat, or concrete.',
        ];
    }
}
