<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 13px;
        color: #1f2937;
        line-height: 1.7;
        direction: rtl;
    }
    .page { padding: 40px 50px; }
    .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
    .header h1 { font-size: 22px; font-weight: bold; color: #4f46e5; margin-bottom: 4px; }
    .header p { color: #6b7280; font-size: 12px; }
    .contract-title { text-align: center; font-size: 18px; font-weight: bold; color: #111827; margin-bottom: 24px; }
    .section { margin-bottom: 22px; }
    .section-title {
        font-size: 14px; font-weight: bold; color: #4f46e5;
        border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 12px;
    }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
    .field label { font-size: 11px; color: #9ca3af; display: block; margin-bottom: 2px; }
    .field span { font-weight: 600; color: #1f2937; }
    .intro-text { color: #374151; margin-bottom: 16px; text-align: justify; }
    .clauses ol { padding-right: 20px; }
    .clauses li { margin-bottom: 10px; color: #374151; }
    .signatures { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 50px; }
    .sig-box { text-align: center; border-top: 1px solid #374151; padding-top: 10px; }
    .sig-box p { font-weight: 600; font-size: 12px; color: #374151; }
    .sig-box span { font-size: 11px; color: #6b7280; }
    .badge {
        display: inline-block; padding: 2px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        background: #d1fae5; color: #065f46;
    }
    .highlight { background: #eef2ff; border-radius: 8px; padding: 12px 16px; margin: 12px 0; }
    .footer { margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 12px; text-align: center; font-size: 10px; color: #9ca3af; }
</style>
</head>
<body>
<div class="page">

    <div class="header">
        <h1>عقاري — Aqari</h1>
        <p>نظام إدارة العقارات</p>
    </div>

    <div class="contract-title">عقد إيجار</div>

    <div class="intro-text">
        تم إبرام هذا العقد بين الطرفين الموضحين أدناه وفقاً لأحكام نظام الإيجار في المملكة العربية السعودية،
        ويُعدّ هذا العقد ملزماً لكلا الطرفين من تاريخ التوقيع عليه.
    </div>

    <div class="section">
        <div class="section-title">الطرف الأول — المؤجر</div>
        <div class="highlight">
            <div class="grid-2">
                <div class="field">
                    <label>العقار</label>
                    <span>{{ $lease->unit->property->name }}</span>
                </div>
                <div class="field">
                    <label>رقم الوحدة</label>
                    <span>{{ $lease->unit->unit_number }}</span>
                </div>
                <div class="field">
                    <label>نوع الوحدة</label>
                    <span>{{ ['apartment'=>'شقة','studio'=>'استوديو','room'=>'غرفة','floor'=>'طابق','shop'=>'محل','suite'=>'جناح'][$lease->unit->type] ?? $lease->unit->type }}</span>
                </div>
                <div class="field">
                    <label>الطابق</label>
                    <span>{{ $lease->unit->floor }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">الطرف الثاني — المستأجر</div>
        <div class="highlight">
            <div class="grid-2">
                <div class="field">
                    <label>الاسم</label>
                    <span>{{ $lease->tenant->name }}</span>
                </div>
                <div class="field">
                    <label>رقم الهوية</label>
                    <span>{{ $lease->tenant->national_id }}</span>
                </div>
                <div class="field">
                    <label>الهاتف</label>
                    <span>{{ $lease->tenant->phone }}</span>
                </div>
                <div class="field">
                    <label>البريد الإلكتروني</label>
                    <span>{{ $lease->tenant->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">تفاصيل العقد</div>
        <div class="grid-2">
            <div class="field">
                <label>تاريخ البداية</label>
                <span>{{ $lease->start_date->format('Y/m/d') }}</span>
            </div>
            <div class="field">
                <label>تاريخ الانتهاء</label>
                <span>{{ $lease->end_date->format('Y/m/d') }}</span>
            </div>
            <div class="field">
                <label>مبلغ الإيجار الشهري</label>
                <span>{{ number_format($lease->rent_amount) }} ريال سعودي</span>
            </div>
            <div class="field">
                <label>مبلغ التأمين</label>
                <span>{{ number_format($lease->deposit_amount ?? 0) }} ريال سعودي</span>
            </div>
            <div class="field">
                <label>يوم السداد الشهري</label>
                <span>اليوم {{ $lease->payment_day }} من كل شهر</span>
            </div>
            <div class="field">
                <label>حالة العقد</label>
                <span class="badge">{{ ['active'=>'نشط','pending'=>'معلق','expired'=>'منتهي','terminated'=>'مُنهى'][$lease->status] ?? $lease->status }}</span>
            </div>
        </div>
    </div>

    <div class="section clauses">
        <div class="section-title">شروط وأحكام العقد</div>
        <ol>
            <li>يلتزم المستأجر بدفع مبلغ الإيجار المتفق عليه في موعده المحدد من كل شهر دون تأخير.</li>
            <li>لا يحق للمستأجر التنازل عن هذا العقد أو تأجير الوحدة من الباطن دون الحصول على موافقة خطية من المؤجر.</li>
            <li>يلتزم المستأجر بالحفاظ على الوحدة في حالة جيدة وإعادتها بالحالة ذاتها عند انتهاء العقد.</li>
            <li>في حال الإخلال بشروط العقد يحق للمؤجر إنهاء العقد مع مصادرة مبلغ التأمين.</li>
            <li>يسري هذا العقد اعتباراً من تاريخ البداية المبيّنة أعلاه وينتهي في تاريخ الانتهاء ما لم يُجدَّد.</li>
            <li>أي إضافات أو تعديلات على هذا العقد يجب أن تكون خطية وموقعة من كلا الطرفين.</li>
        </ol>
    </div>

    @if($lease->notes)
    <div class="section">
        <div class="section-title">ملاحظات خاصة</div>
        <p style="color:#374151">{{ $lease->notes }}</p>
    </div>
    @endif

    <div class="signatures">
        <div class="sig-box">
            <p>الطرف الأول (المؤجر)</p>
            <br><br>
            <span>التوقيع: ________________</span>
        </div>
        <div class="sig-box">
            <p>الطرف الثاني (المستأجر)</p>
            <span>{{ $lease->tenant->name }}</span><br>
            <span>التوقيع: ________________</span>
        </div>
    </div>

    <div class="footer">
        تم إنشاء هذا العقد بتاريخ {{ now()->format('Y/m/d') }} · عقاري — نظام إدارة العقارات
    </div>

</div>
</body>
</html>
