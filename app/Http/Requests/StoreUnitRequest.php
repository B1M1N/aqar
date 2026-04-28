<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('units.create');
    }

    public function rules(): array
    {
        return [
            'property_id'  => ['required', 'exists:properties,id'],
            'unit_number'  => ['required', 'string', 'max:50'],
            'type'         => ['required', 'in:apartment,studio,room,floor,shop,suite'],
            'floor'        => ['required', 'integer', 'min:0', 'max:200'],
            'area'         => ['required', 'numeric', 'min:1', 'max:99999'],
            'bedrooms'     => ['required', 'integer', 'min:0', 'max:20'],
            'bathrooms'    => ['required', 'integer', 'min:0', 'max:20'],
            'rent_price'   => ['required', 'numeric', 'min:0'],
            'rent_period'  => ['required', 'in:monthly,quarterly,yearly'],
            'status'       => ['required', 'in:available,occupied,reserved,maintenance'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'features'     => ['nullable', 'array'],
            'features.*'   => ['string', 'max:100'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'property_id' => 'العقار',
            'unit_number' => 'رقم الوحدة',
            'type'        => 'نوع الوحدة',
            'floor'       => 'الطابق',
            'area'        => 'المساحة',
            'rent_price'  => 'سعر الإيجار',
            'rent_period' => 'فترة الإيجار',
            'status'      => 'الحالة',
            'images.*'    => 'الصورة',
        ];
    }
}
