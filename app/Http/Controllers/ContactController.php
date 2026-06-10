<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactInquiryMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('marketing/Contact', [
            'status' => session('status'),
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            Mail::to(config('mail.contact_recipient', 'info@varidianlab.com'))
                ->send(new ContactInquiryMail($validated));
        } catch (\Throwable $e) {
            Log::error('Contact form mail failed', ['error' => $e->getMessage()]);
        }

        return back()->with('status', 'message-sent');
    }
}
