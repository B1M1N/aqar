<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('leases.edit');
    }

    public function rules(): array
    {
        return [
            'unit_id'        => ['required', 'exists:units,id'],
            'tenant_id'      => ['required', 'exists:tenants,id'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after:start_date'],
            'rent_amount'    => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_day'    => ['required', 'integer', 'min:1', 'max:28'],
            'status'         => ['required', 'in:active,pending,expired,terminated'],
            'notes'          => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'unit_id'     => 'الوحدة',
            'tenant_id'   => 'المستأجر',
            'start_date'  => 'تاريخ البداية',
            'end_date'    => 'تاريخ الانتهاء',
            'rent_amount' => 'مبلغ الإيجار',
            'payment_day' => 'يوم السداد',
        ];
    }
}
