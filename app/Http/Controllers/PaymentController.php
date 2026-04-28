<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\MoyasarService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, MoyasarService $moyasar)
    {
        $this->authorize('payments.create');

        $data = $request->validate([
            'invoice_id'     => ['required', 'exists:invoices,id'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'method'         => ['required', 'in:cash,bank_transfer,cheque,online'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'reference'      => ['nullable', 'string', 'max:200'],
        ]);

        $invoice = Invoice::findOrFail($data['invoice_id']);

        if ($invoice->status === 'paid') {
            return back()->with('error', 'الفاتورة مدفوعة مسبقاً.');
        }

        $moyasar->markInvoicePaid($invoice, [
            'source' => ['type' => $data['method'], 'message' => $data['reference'] ?? null],
            'id'     => $data['transaction_id'] ?? ('MAN-' . now()->format('YmdHis')),
        ]);

        return back()->with('success', 'تم تسجيل الدفع بنجاح.');
    }

    public function moyasarWebhook(Request $request)
    {
        $data = $request->all();

        if (($data['status'] ?? '') === 'paid') {
            $invoiceId = $data['metadata']['invoice_id'] ?? null;
            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice && $invoice->status !== 'paid') {
                    app(MoyasarService::class)->markInvoicePaid($invoice, $data);
                }
            }
        }

        return response('', 200);
    }
}
