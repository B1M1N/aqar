@extends('layouts.app')
@section('title', 'تعديل عقار')
@section('page-title', 'تعديل: ' . $property->name)
@section('breadcrumb')
    <a href="{{ route('properties.index') }}" class="hover:text-gray-600">العقارات</a>
    <span class="mx-1">/</span>
    <a href="{{ route('properties.show', $property) }}" class="hover:text-gray-600">{{ $property->name }}</a>
    <span class="mx-1">/</span><span class="text-gray-700">تعديل</span>
@endsection

@section('content')
<form method="POST" action="{{ route('properties.update', $property) }}"
      enctype="multipart/form-data">
@csrf @method('PUT')

<div class="grid gap-5 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-5">

        <div class="card p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3">المعلومات الأساسية</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">اسم العقار <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $property->name) }}"
                           class="form-input @error('name') border-red-400 @enderror" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">النوع <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input" required>
                        @foreach(['building' => 'مبنى', 'apartment' => 'شقة', 'villa' => 'فيلا', 'hotel' => 'فندق'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('type', $property->type) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="active"            @selected(old('status', $property->status) === 'active')>نشط</option>
                        <option value="inactive"          @selected(old('status', $property->status) === 'inactive')>غير نشط</option>
                        <option value="under_maintenance" @selected(old('status', $property->status) === 'under_maintenance')>تحت الصيانة</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">عدد الطوابق <span class="text-red-500">*</span></label>
                    <input type="number" name="floors" value="{{ old('floors', $property->floors) }}"
                           min="1" max="200" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">سنة البناء</label>
                    <input type="number" name="build_year" value="{{ old('build_year', $property->build_year) }}"
                           min="1900" max="{{ date('Y') }}" class="form-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $property->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3">الموقع</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">العنوان الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $property->address) }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">المدينة <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $property->city) }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">الحي</label>
                    <input type="text" name="district" value="{{ old('district', $property->district) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">خط العرض</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude', $property->latitude) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">خط الطول</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude', $property->longitude) }}"
                           class="form-input">
                </div>
            </div>
        </div>

        {{-- Existing images --}}
        @if($property->images && count($property->images) > 0)
        <div class="card p-6" x-data="{ images: @json($property->images), removed: [] }">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">الصور الحالية</h3>
            <div class="grid grid-cols-4 gap-3">
                <template x-for="(img, i) in images" :key="i">
                    <div class="relative rounded-xl overflow-hidden aspect-square bg-gray-100"
                         :class="removed.includes(img) && 'opacity-30'">
                        <img :src="'/storage/' + img" class="h-full w-full object-cover">
                        <button type="button"
                                @click="removed.includes(img) ? removed.splice(removed.indexOf(img),1) : removed.push(img)"
                                :class="removed.includes(img) ? 'bg-emerald-500' : 'bg-red-500'"
                                class="absolute top-1 end-1 flex h-6 w-6 items-center justify-center rounded-full text-white">
                            <template x-if="!removed.includes(img)">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </template>
                            <template x-if="removed.includes(img)">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l16 16M4 20L20 4"/>
                                </svg>
                            </template>
                        </button>
                        <input type="hidden" :name="'remove_images[]'" :value="img" x-show="removed.includes(img)">
                    </div>
                </template>
            </div>
            <p class="mt-2 text-xs text-gray-400">انقر على الصورة لتحديدها للحذف — ستُحذف عند الحفظ</p>
        </div>
        @endif

        {{-- New images --}}
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">إضافة صور جديدة</h3>
            <label class="flex flex-col items-center justify-center gap-2 h-28 rounded-xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition">
                <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-sm text-gray-500">انقر لاختيار صور إضافية</span>
                <input type="file" name="images[]" multiple accept="image/*" class="hidden">
            </label>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">
        <div class="card p-6" x-data="amenitiesEdit()">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">المرافق والخدمات</h3>
            <div class="space-y-3">
                <div class="flex gap-2">
                    <input type="text" x-model="newAmenity" @keydown.enter.prevent="add()"
                           placeholder="أضف ميزة..." class="form-input flex-1">
                    <button type="button" @click="add()" class="btn-secondary px-3">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
                <div class="space-y-1.5" x-show="amenities.length > 0">
                    <template x-for="(a, i) in amenities" :key="i">
                        <div class="flex items-center justify-between rounded-lg bg-indigo-50 px-3 py-1.5">
                            <input type="hidden" :name="'amenities[' + i + ']'" :value="a">
                            <span class="text-sm text-indigo-700" x-text="a"></span>
                            <button type="button" @click="amenities.splice(i,1)"
                                    class="text-indigo-400 hover:text-red-500 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="card p-5 flex flex-col gap-3">
            <button type="submit" class="btn-primary w-full justify-center py-3">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ التعديلات
            </button>
            <a href="{{ route('properties.show', $property) }}" class="btn-secondary w-full justify-center">إلغاء</a>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
function amenitiesEdit() {
    return {
        amenities: @json(old('amenities', $property->amenities ?? [])),
        newAmenity: '',
        add() {
            const v = this.newAmenity.trim();
            if (v && !this.amenities.includes(v)) this.amenities.push(v);
            this.newAmenity = '';
        }
    };
}
</script>
@endpush
