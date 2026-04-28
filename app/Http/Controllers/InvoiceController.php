<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use App\Services\MoyasarService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $invoices = Invoice::query()
            ->when($request->search, fn ($q) =>
                $q->whereHas('tenant', fn ($t) => $t->where('name', 'like', "%{$request->search}%"))
                  ->orWhere('id', $request->search)
            )
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->type,   fn ($q) => $q->where('type', $request->type))
            ->with(['tenant', 'unit.property', 'lease'])
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request): View
    {
        $leases  = Lease::where('status', 'active')->with(['unit.property', 'tenant'])->get();
        $tenants = Tenant::orderBy('name')->get(['id', 'name']);
        $units   = Unit::with('property')->get(['id', 'unit_number', 'property_id']);

        $selectedLease = $request->lease_id ? Lease::with(['unit.property', 'tenant'])->find($request->lease_id) : null;

        return view('invoices.create', compact('leases', 'tenants', 'units', 'selectedLease'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('invoices.create');

        $data = $request->validate([
            'lease_id'  => ['required', 'exists:leases,id'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'unit_id'   => ['required', 'exists:units,id'],
            'amount'    => ['required', 'numeric', 'min:0'],
            'due_date'  => ['required', 'date'],
            'type'      => ['required', 'in:rent,maintenance,utility,other'],
            'status'    => ['required', 'in:draft,pending,paid,late,cancelled'],
            'notes'     => ['nullable', 'string', 'max:2000'],
        ]);

        $invoice = Invoice::create($data);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم إنشاء الفاتورة بنجاح.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['lease.unit.property', 'tenant', 'unit.property', 'payments.paidBy']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $this->authorize('invoices.edit');
        $leases  = Lease::with(['unit.property', 'tenant'])->get();
        $tenants = Tenant::orderBy('name')->get(['id', 'name']);
        $units   = Unit::with('property')->get(['id', 'unit_number', 'property_id']);

        return view('invoices.edit', compact('invoice', 'leases', 'tenants', 'units'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $this->authorize('invoices.edit');

        $data = $request->validate([
            'lease_id'  => ['required', 'exists:leases,id'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'unit_id'   => ['required', 'exists:units,id'],
            'amount'    => ['required', 'numeric', 'min:0'],
            'due_date'  => ['required', 'date'],
            'type'      => ['required', 'in:rent,maintenance,utility,other'],
            'status'    => ['required', 'in:draft,pending,paid,late,cancelled'],
            'notes'     => ['nullable', 'string', 'max:2000'],
        ]);

        $invoice->update($data);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->authorize('invoices.delete');
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح.');
    }

    public function pay(Request $request, Invoice $invoice, MoyasarService $moyasar): RedirectResponse
    {
        $this->authorize('invoices.pay');

        if ($invoice->status === 'paid') {
            return back()->with('error', 'الفاتورة مدفوعة مسبقاً.');
        }

        if ($request->method === 'cash') {
            $moyasar->markInvoicePaid($invoice, [
                'source' => ['type' => 'cash'],
                'id'     => 'CASH-' . now()->format('YmdHis'),
            ]);
            return back()->with('success', 'تم تسجيل الدفع نقداً بنجاح.');
        }

        $url = $moyasar->createPaymentUrl($invoice);
        return redirect($url);
    }

    public function moyasarWebhook(Request $request): \Illuminate\Http\Response
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

    public function generatePdf(Invoice $invoice)
    {
        $this->authorize('invoices.generate-pdf');
        $invoice->load(['lease.unit.property', 'tenant', 'payments']);

        $pdf  = Pdf::loadView('pdf.receipt', compact('invoice'))->setPaper('a4', 'portrait');
        $path = 'invoices/receipt-' . $invoice->id . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return Storage::disk('public')->download($path, 'فاتورة-' . $invoice->invoice_number . '.pdf');
    }
}
