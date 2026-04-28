<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('tenants.create');
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:100'],
            'email'             => ['required', 'email', 'max:150', 'unique:tenants,email'],
            'phone'             => ['required', 'string', 'max:20'],
            'national_id'       => ['required', 'string', 'max:50', 'unique:tenants,national_id'],
            'nationality'       => ['nullable', 'string', 'max:60'],
            'id_type'           => ['required', 'in:national_id,passport,iqama'],
            'id_expiry'         => ['nullable', 'date'],
            'emergency_contact' => ['nullable', 'string', 'max:100'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'الاسم',
            'email'       => 'البريد الإلكتروني',
            'phone'       => 'الهاتف',
            'national_id' => 'رقم الهوية',
            'id_type'     => 'نوع الهوية',
        ];
    }
}
