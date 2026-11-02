<?php

namespace App\Http\Controllers\Partners;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\LeadRequest;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    /**
     * Register a lead in writing; staff confirm ownership or flag it as already taken.
     */
    public function store(LeadRequest $request): RedirectResponse
    {
        $request->user()->partner->leads()->create([
            ...$request->validated(),
            'status' => LeadStatus::Pending,
        ]);

        return back();
    }
}
