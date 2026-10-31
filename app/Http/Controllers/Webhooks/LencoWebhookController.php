<?php

namespace App\Http\Controllers\Webhooks;

use App\Actions\Billing\ConfirmPayment;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Lenco\LencoClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LencoWebhookController extends Controller
{
    /**
     * Lenco retries until it gets a 2xx, so only return one once the event is handled.
     */
    public function __invoke(Request $request, LencoClient $lenco, ConfirmPayment $confirmPayment): JsonResponse
    {
        abort_unless($lenco->hasValidSignature($request->getContent(), $request->header('X-Lenco-Signature')), 401);

        if (Str::startsWith((string) $request->input('event'), 'collection.')) {
            $payment = Payment::where('reference', $request->input('data.reference'))->first();

            if ($payment) {
                $confirmPayment->handle($payment);
            }
        }

        return response()->json(['received' => true]);
    }
}
