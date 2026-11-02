@php($isReceipt = $invoice->status === \App\Enums\InvoiceStatus::Paid)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isReceipt ? 'Receipt' : 'Invoice' }} {{ $invoice->number }} — {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px 16px; background: #f4f4f5; color: #18181b; font: 14px/1.5 system-ui, -apple-system, "Segoe UI", sans-serif; }
        .sheet { max-width: 760px; margin: 0 auto; padding: 48px; background: #fff; border-radius: 8px; }
        header { display: flex; justify-content: space-between; gap: 24px; flex-wrap: wrap; margin-bottom: 40px; }
        h1 { margin: 0; font-size: 28px; letter-spacing: -0.02em; }
        .muted { color: #71717a; }
        .status { display: inline-block; margin-top: 8px; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: #f4f4f5; }
        .status--paid { background: #dcfce7; color: #166534; }
        .parties { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-bottom: 32px; }
        .label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; color: #71717a; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { padding: 12px 0; text-align: left; border-bottom: 1px solid #e4e4e7; }
        th:last-child, td:last-child { text-align: right; }
        .total td { border-bottom: 0; font-size: 18px; font-weight: 700; }
        footer { margin-top: 40px; font-size: 12px; color: #71717a; }
        .actions { max-width: 760px; margin: 0 auto 16px; text-align: right; }
        .actions button { padding: 8px 16px; border: 0; border-radius: 6px; background: #18181b; color: #fff; font: inherit; cursor: pointer; }
        @media print {
            body { padding: 0; background: #fff; }
            .sheet { padding: 0; border-radius: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="actions"><button type="button" onclick="window.print()">Print or save as PDF</button></div>

    <main class="sheet">
        <header>
            <div>
                <h1>{{ $isReceipt ? 'Receipt' : 'Invoice' }}</h1>
                <div class="muted">{{ $invoice->number }}</div>
                <span class="status {{ $isReceipt ? 'status--paid' : '' }}">{{ $invoice->status->label() }}</span>
            </div>
            <div style="text-align: right">
                <strong>{{ config('app.name') }}</strong><br>
                <span class="muted">Lusaka, Zambia<br>{{ $contact['email'] }}<br>{{ $contact['phone'] }}</span>
            </div>
        </header>

        <section class="parties">
            <div>
                <div class="label">Billed to</div>
                <strong>{{ $team->name }}</strong><br>
                <span class="muted">{{ $team->owner()?->email }}</span>
            </div>
            <div>
                <div class="label">Issued</div>
                {{ $invoice->created_at->toFormattedDateString() }}
                <div class="label" style="margin-top: 12px">{{ $isReceipt ? 'Paid' : 'Due' }}</div>
                {{ ($isReceipt ? $invoice->paid_at : $invoice->due_at)->toFormattedDateString() }}
            </div>
            @if ($payment)
                <div>
                    <div class="label">Payment</div>
                    {{ str($payment->channel ?? 'online')->replace('-', ' ')->title() }}<br>
                    <span class="muted">Ref {{ $payment->lenco_reference ?? $payment->reference }}</span>
                </div>
            @endif
        </section>

        <table>
            <thead>
                <tr><th>Description</th><th>Amount</th></tr>
            </thead>
            <tbody>
                <tr><td>{{ $invoice->description }}</td><td>ZMW {{ $invoice->formattedAmount() }}</td></tr>
                <tr class="total"><td>{{ $isReceipt ? 'Total paid' : 'Total due' }}</td><td>ZMW {{ $invoice->formattedAmount() }}</td></tr>
            </tbody>
        </table>

        <footer>Prices are in Zambian kwacha. Thank you for hosting with {{ config('app.name') }}.</footer>
    </main>
</body>
</html>
