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
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #4f46e5; }
    .header h1 { font-size: 20px; font-weight: bold; color: #4f46e5; }
    .header p { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .receipt-title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 24px; }
    .status-badge {
        display: inline-block; padding: 4px 14px; border-radius: 20px;
        font-size: 12px; font-weight: bold;
    }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-late { background: #fee2e2; color: #991b1b; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 32px; margin-bottom: 24px; }
    .info-item label { font-size: 11px; color: #9ca3af; display: block; margin-bottom: 2px; }
    .info-item span { font-weight: 600; }
    .amount-box {
        background: #eef2ff; border-radius: 12px; padding: 20px 24px;
        text-align: center; margin: 24px 0;
    }
    .amount-box p { font-size: 11px; color: #6b7280; margin-bottom: 4px; }
    .amount-box .amount { font-size: 28px; font-weight: bold; color: #4f46e5; }
    .payments-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    .payments-table th { background: #f9fafb; padding: 8px 12px; font-size: 11px; text-align: right; border-bottom: 1px solid #e5e7eb; }
    .payments-table td { padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f3f4f6; }
    .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 12px; }
    .section-title { font-size: 12px; font-weight: bold; color: #4f46e5; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
</style>
</head>
<body>
<div class="page">

    <div class="header">
        <div>
            <h1>عقاري — Aqari</h1>
            <p>نظام إدارة العقارات</p>
        </div>
        <div style="text-align:left">
            <p style="font-size:11px;color:#6b7280;">تاريخ الإصدار</p>
            <p style="font-weight:600;">{{ now()->format('Y/m/d') }}</p>
        </div>
    </div>

    <div class="receipt-title">إيصال دفع فاتورة</div>

    <div style="margin-bottom:20px">
        <span class="status-badge {{ $invoice->status === 'paid' ? 'status-paid' : ($invoice->is_late ? 'status-late' : 'status-pending') }}">
            {{ ['paid'=>'مدفوعة','pending'=>'غير مدفوعة','late'=>'متأخرة','draft'=>'مسودة','cancelled'=>'ملغاة'][$invoice->status] ?? $invoice->status }}
        </span>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <label>رقم الفاتورة</label>
            <span>{{ $invoice->invoice_number }}</span>
        </div>
        <div class="info-item">
            <label>المستأجر</label>
            <span>{{ $invoice->tenant->name }}</span>
        </div>
        <div class="info-item">
            <label>الوحدة</label>
            <span>{{ $invoice->unit->unit_number }} — {{ $invoice->unit->property->name }}</span>
        </div>
        <div class="info-item">
            <label>نوع الفاتورة</label>
            <span>{{ ['rent'=>'إيجار','maintenance'=>'صيانة','utility'=>'خدمات','other'=>'أخرى'][$invoice->type] ?? $invoice->type }}</span>
        </div>
        <div class="info-item">
            <label>تاريخ الاستحقاق</label>
            <span>{{ $invoice->due_date->format('Y/m/d') }}</span>
        </div>
        <div class="info-item">
            <label>تاريخ السداد</label>
            <span>{{ $invoice->paid_date?->format('Y/m/d') ?? '—' }}</span>
        </div>
    </div>

    <div class="amount-box">
        <p>إجمالي المبلغ</p>
        <div class="amount">{{ number_format($invoice->amount) }} ر.س</div>
    </div>

    @if($invoice->payments->isNotEmpty())
    <div>
        <div class="section-title">سجل الدفعات</div>
        <table class="payments-table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المبلغ</th>
                    <th>طريقة الدفع</th>
                    <th>رقم العملية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('Y/m/d H:i') }}</td>
                    <td style="font-weight:600">{{ number_format($payment->amount) }} ر.س</td>
                    <td>{{ ['cash'=>'نقدًا','bank_transfer'=>'تحويل بنكي','cheque'=>'شيك','online'=>'إلكتروني'][$payment->method] ?? $payment->method }}</td>
                    <td style="font-family:monospace;font-size:11px">{{ $payment->transaction_id ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($invoice->notes)
    <div style="margin-top:20px">
        <div class="section-title">ملاحظات</div>
        <p style="color:#374151;font-size:12px">{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        تم إنشاء هذا الإيصال بتاريخ {{ now()->format('Y/m/d H:i') }} · عقاري — نظام إدارة العقارات
    </div>

</div>
</body>
</html>
