<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Partners\PayOutCommission;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function store(Request $request, Partner $partner, PayOutCommission $payOutCommission): RedirectResponse
    {
        $validated = $request->validate([
            'method' => ['required', 'in:mobile-money,bank-transfer'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $payOutCommission->handle($partner, $validated['method'], $validated['reference'] ?? null, $request->user());

        return back();
    }
}
