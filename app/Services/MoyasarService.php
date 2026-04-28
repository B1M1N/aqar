<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoyasarService
{
    private string $baseUrl = 'https://api.moyasar.com/v1';
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.moyasar.secret_key', '');
    }

    public function createPaymentUrl(Invoice $invoice): string
    {
        $amount = (int) ($invoice->amount * 100); // Moyasar uses halalas

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post("{$this->baseUrl}/invoices", [
                'amount'      => $amount,
                'currency'    => 'SAR',
                'description' => "فاتورة #{$invoice->id} - {$invoice->tenant->name}",
                'callback_url'=> route('payments.webhook'),
                'metadata'    => ['invoice_id' => $invoice->id],
            ]);

        if ($response->successful()) {
            return $response->json('url');
        }

        Log::error('Moyasar invoice creation failed', $response->json());
        throw new \RuntimeException('فشل إنشاء رابط الدفع');
    }

    public function verifyPayment(string $paymentId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->get("{$this->baseUrl}/payments/{$paymentId}");

        return $response->json();
    }

    public function markInvoicePaid(Invoice $invoice, array $paymentData): Payment
    {
        $payment = Payment::create([
            'invoice_id'     => $invoice->id,
            'amount'         => $invoice->amount,
            'method'         => $paymentData['source']['type'] ?? 'online',
            'transaction_id' => $paymentData['id'] ?? null,
            'reference'      => $paymentData['source']['message'] ?? null,
            'paid_by'        => auth()->id(),
        ]);

        $invoice->update([
            'status'    => 'paid',
            'paid_date' => now(),
        ]);

        return $payment;
    }
}
