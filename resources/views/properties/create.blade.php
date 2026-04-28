@extends('layouts.app')
@section('title', 'إضافة عقار')
@section('page-title', 'إضافة عقار جديد')
@section('breadcrumb')
    <a href="{{ route('properties.index') }}" class="hover:text-gray-600">العقارات</a>
    <span class="mx-1">/</span><span class="text-gray-700">إضافة</span>
@endsection

@section('content')
<form method="POST" action="{{ route('properties.store') }}"
      enctype="multipart/form-data" x-data="propertyForm()">
@csrf

<div class="grid gap-5 lg:grid-cols-3">

    {{-- Main fields --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Basic info --}}
        <div class="card p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3">المعلومات الأساسية</h3>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">اسم العقار <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-input @error('name') border-red-400 @enderror" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">النوع <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input @error('type') border-red-400 @enderror" required>
                        <option value="">-- اختر --</option>
                        @foreach(['building' => 'مبنى', 'apartment' => 'شقة', 'villa' => 'فيلا', 'hotel' => 'فندق'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('type') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input @error('status') border-red-400 @enderror" required>
                        <option value="active"            @selected(old('status','active') === 'active')>نشط</option>
                        <option value="inactive"          @selected(old('status') === 'inactive')>غير نشط</option>
                        <option value="under_maintenance" @selected(old('status') === 'under_maintenance')>تحت الصيانة</option>
                    </select>
                    @error('status')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">عدد الطوابق <span class="text-red-500">*</span></label>
                    <input type="number" name="floors" value="{{ old('floors', 1) }}"
                           min="1" max="200"
                           class="form-input @error('floors') border-red-400 @enderror" required>
                    @error('floors')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">سنة البناء</label>
                    <input type="number" name="build_year" value="{{ old('build_year') }}"
                           min="1900" max="{{ date('Y') }}"
                           class="form-input @error('build_year') border-red-400 @enderror">
                    @error('build_year')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="3"
                              class="form-input @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="card p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3">الموقع</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">العنوان الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="form-input @error('address') border-red-400 @enderror" required>
                    @error('address')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">المدينة <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="form-input @error('city') border-red-400 @enderror" required>
                    @error('city')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">الحي</label>
                    <input type="text" name="district" value="{{ old('district') }}"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">خط العرض</label>
                    <input type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                           class="form-input @error('latitude') border-red-400 @enderror">
                    @error('latitude')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">خط الطول</label>
                    <input type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                           class="form-input @error('longitude') border-red-400 @enderror">
                    @error('longitude')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">الصور</h3>
            <div x-data="imageUploader()" class="space-y-3">
                <label class="flex flex-col items-center justify-center gap-2 h-32 rounded-xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-500">انقر لاختيار الصور أو اسحب وأفلت</span>
                    <span class="text-xs text-gray-400">JPG, PNG, WEBP — حجم أقصى 5MB لكل صورة</span>
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="hidden" @change="previewImages($event)">
                </label>
                <div class="grid grid-cols-4 gap-3" x-show="previews.length > 0">
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="relative rounded-xl overflow-hidden aspect-square bg-gray-100">
                            <img :src="src" class="h-full w-full object-cover">
                            <button type="button" @click="removePreview(i)"
                                    class="absolute top-1 end-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white hover:bg-red-600">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Amenities --}}
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3 mb-4">المرافق والخدمات</h3>
            <div x-data="amenitiesManager()" class="space-y-3">
                <div class="flex gap-2">
                    <input type="text" x-model="newAmenity" @keydown.enter.prevent="add()"
                           placeholder="أضف ميزة..." class="form-input flex-1">
                    <button type="button" @click="add()" class="btn-secondary px-3">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
                {{-- Suggested --}}
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['موقف سيارات', 'مسبح', 'صالة رياضية', 'أمن 24/7', 'مصعد', 'تكييف مركزي', 'إنترنت', 'حديقة', 'غرفة حراسة', 'خزان مياه'] as $a)
                    <button type="button" @click="toggle('{{ $a }}')"
                            :class="amenities.includes('{{ $a }}') ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-gray-50 text-gray-600 border-gray-200'"
                            class="text-xs px-2.5 py-1 rounded-full border transition">
                        {{ $a }}
                    </button>
                    @endforeach
                </div>
                {{-- Selected --}}
                <div class="space-y-1.5" x-show="amenities.length > 0">
                    <template x-for="(a, i) in amenities" :key="i">
                        <div class="flex items-center justify-between rounded-lg bg-indigo-50 px-3 py-1.5">
                            <input type="hidden" :name="'amenities[' + i + ']'" :value="a">
                            <span class="text-sm text-indigo-700" x-text="a"></span>
                            <button type="button" @click="amenities.splice(i, 1)"
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

        {{-- Submit --}}
        <div class="card p-5 flex flex-col gap-3">
            <button type="submit" class="btn-primary w-full justify-center py-3">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ العقار
            </button>
            <a href="{{ route('properties.index') }}" class="btn-secondary w-full justify-center">إلغاء</a>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
function imageUploader() {
    return {
        previews: [],
        previewImages(e) {
            const files = Array.from(e.target.files);
            files.forEach(f => {
                const reader = new FileReader();
                reader.onload = ev => this.previews.push(ev.target.result);
                reader.readAsDataURL(f);
            });
        },
        removePreview(i) { this.previews.splice(i, 1); }
    };
}
function amenitiesManager() {
    return {
        amenities: @json(old('amenities', [])),
        newAmenity: '',
        add() {
            const v = this.newAmenity.trim();
            if (v && !this.amenities.includes(v)) this.amenities.push(v);
            this.newAmenity = '';
        },
        toggle(a) {
            const i = this.amenities.indexOf(a);
            i === -1 ? this.amenities.push(a) : this.amenities.splice(i, 1);
        }
    };
}
function propertyForm() { return {}; }
</script>
@endpush
