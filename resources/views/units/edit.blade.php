@extends('layouts.app')
@section('title', 'تعديل وحدة')
@section('page-title', 'تعديل الوحدة — ' . $unit->unit_number)
@section('breadcrumb')
    <a href="{{ route('units.index') }}" class="hover:text-gray-700">الوحدات</a>
    <span class="mx-1">/</span>
    <a href="{{ route('units.show', $unit) }}" class="hover:text-gray-700">{{ $unit->unit_number }}</a>
    <span class="mx-1">/</span><span class="text-gray-700">تعديل</span>
@endsection

@section('content')
<form method="POST" action="{{ route('units.update', $unit) }}" enctype="multipart/form-data" class="space-y-6">
@csrf @method('PUT')

    {{-- Basic Info --}}
    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">المعلومات الأساسية</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">العقار <span class="text-red-500">*</span></label>
                <select name="property_id" class="form-input" required>
                    <option value="">اختر العقار</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}"
                            @selected(old('property_id', $unit->property_id) == $p->id)>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                @error('property_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">رقم الوحدة <span class="text-red-500">*</span></label>
                <input type="text" name="unit_number"
                       value="{{ old('unit_number', $unit->unit_number) }}"
                       class="form-input" required>
                @error('unit_number')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">نوع الوحدة <span class="text-red-500">*</span></label>
                <select name="type" class="form-input" required>
                    <option value="apartment" @selected(old('type',$unit->type) === 'apartment')>شقة</option>
                    <option value="studio"    @selected(old('type',$unit->type) === 'studio')>استوديو</option>
                    <option value="room"      @selected(old('type',$unit->type) === 'room')>غرفة</option>
                    <option value="floor"     @selected(old('type',$unit->type) === 'floor')>طابق</option>
                    <option value="shop"      @selected(old('type',$unit->type) === 'shop')>محل</option>
                    <option value="suite"     @selected(old('type',$unit->type) === 'suite')>جناح</option>
                </select>
                @error('type')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="available"   @selected(old('status',$unit->status) === 'available')>متاحة</option>
                    <option value="occupied"    @selected(old('status',$unit->status) === 'occupied')>مشغولة</option>
                    <option value="reserved"    @selected(old('status',$unit->status) === 'reserved')>محجوزة</option>
                    <option value="maintenance" @selected(old('status',$unit->status) === 'maintenance')>صيانة</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">الطابق <span class="text-red-500">*</span></label>
                <input type="number" name="floor"
                       value="{{ old('floor', $unit->floor) }}"
                       min="0" max="200" class="form-input" required>
                @error('floor')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">المساحة (م²) <span class="text-red-500">*</span></label>
                <input type="number" name="area"
                       value="{{ old('area', $unit->area) }}"
                       min="1" max="99999" step="0.01" class="form-input" required>
                @error('area')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">عدد غرف النوم <span class="text-red-500">*</span></label>
                <input type="number" name="bedrooms"
                       value="{{ old('bedrooms', $unit->bedrooms) }}"
                       min="0" max="20" class="form-input" required>
                @error('bedrooms')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">عدد الحمامات <span class="text-red-500">*</span></label>
                <input type="number" name="bathrooms"
                       value="{{ old('bathrooms', $unit->bathrooms) }}"
                       min="0" max="20" class="form-input" required>
                @error('bathrooms')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Pricing --}}
    <div class="card p-6 space-y-5">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">الإيجار</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="form-label">سعر الإيجار (ر.س) <span class="text-red-500">*</span></label>
                <input type="number" name="rent_price"
                       value="{{ old('rent_price', $unit->rent_price) }}"
                       min="0" step="0.01" class="form-input" required>
                @error('rent_price')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">فترة الإيجار <span class="text-red-500">*</span></label>
                <select name="rent_period" class="form-input" required>
                    <option value="monthly"   @selected(old('rent_period',$unit->rent_period) === 'monthly')>شهري</option>
                    <option value="quarterly" @selected(old('rent_period',$unit->rent_period) === 'quarterly')>ربع سنوي</option>
                    <option value="yearly"    @selected(old('rent_period',$unit->rent_period) === 'yearly')>سنوي</option>
                </select>
                @error('rent_period')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Features --}}
    <div class="card p-6 space-y-4" x-data="featuresManager(@js(old('features', $unit->features ?? [])))">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">المميزات</h3>

        <div class="flex gap-2">
            <input type="text" x-model="newFeature" @keydown.enter.prevent="add()"
                   placeholder="أضف ميزة" class="form-input">
            <button type="button" @click="add()" class="btn-secondary whitespace-nowrap">إضافة</button>
        </div>

        <div class="flex flex-wrap gap-2">
            <template x-for="(f, i) in features" :key="i">
                <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-sm px-3 py-1 rounded-full">
                    <span x-text="f"></span>
                    <button type="button" @click="remove(i)" class="text-indigo-400 hover:text-indigo-600">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <input type="hidden" :name="'features['+i+']'" :value="f">
                </span>
            </template>
        </div>
    </div>

    {{-- Notes --}}
    <div class="card p-6 space-y-4">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">ملاحظات</h3>
        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $unit->notes) }}</textarea>
        @error('notes')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Existing Images --}}
    @if($unit->images && count($unit->images))
    <div class="card p-6 space-y-4" x-data="{ removed: [] }">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">الصور الحالية</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($unit->images as $img)
            <div x-data="{ path: '{{ $img }}', marked: false }" class="relative group">
                <img src="{{ Storage::url($img) }}" alt=""
                     class="w-full h-28 object-cover rounded-xl border border-gray-200"
                     :class="marked ? 'opacity-40' : ''">
                <button type="button"
                        @click="marked = !marked; marked ? removed.push(path) : removed.splice(removed.indexOf(path),1)"
                        class="absolute top-1.5 end-1.5 bg-white/80 hover:bg-red-50 text-gray-500 hover:text-red-600 rounded-full p-1 shadow transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <input type="hidden" x-show="marked" name="remove_images[]" :value="path">
            </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-400">اضغط على X لتحديد الصور المراد حذفها عند الحفظ</p>
    </div>
    @endif

    {{-- New Images --}}
    <div class="card p-6 space-y-4">
        <h3 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">إضافة صور جديدة</h3>
        <input type="file" name="images[]" accept="image/*" multiple class="form-input">
        <p class="text-xs text-gray-400">JPG, PNG, WEBP — حجم أقصى 5 ميجا لكل صورة</p>
        @error('images.*')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('units.show', $unit) }}" class="btn-secondary">إلغاء</a>
        <button type="submit" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            حفظ التعديلات
        </button>
    </div>

</form>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('featuresManager', (initial) => ({
        features: initial,
        newFeature: '',
        add() {
            const v = this.newFeature.trim();
            if (v && !this.features.includes(v)) this.features.push(v);
            this.newFeature = '';
        },
        remove(i) { this.features.splice(i, 1); },
    }));
});
</script>
@endpush
