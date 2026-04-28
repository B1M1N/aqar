<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('properties.create');
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:building,apartment,villa,hotel'],
            'description' => ['nullable', 'string', 'max:2000'],
            'address'     => ['required', 'string', 'max:500'],
            'city'        => ['required', 'string', 'max:100'],
            'district'    => ['nullable', 'string', 'max:100'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
            'floors'      => ['required', 'integer', 'min:1', 'max:200'],
            'build_year'  => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'status'      => ['required', 'in:active,inactive,under_maintenance'],
            'amenities'   => ['nullable', 'array'],
            'amenities.*' => ['string', 'max:100'],
            'images'      => ['nullable', 'array', 'max:10'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'اسم العقار',
            'type'       => 'نوع العقار',
            'address'    => 'العنوان',
            'city'       => 'المدينة',
            'floors'     => 'عدد الطوابق',
            'status'     => 'الحالة',
            'images.*'   => 'الصورة',
        ];
    }
}
