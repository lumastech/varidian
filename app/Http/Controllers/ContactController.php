<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('marketing/Contact');
    }

    public function store(ContactRequest $request): \Illuminate\Http\RedirectResponse
    {
        // Contact form submission received — extend here to send email via Mail::to() etc.

        return back()->with('status', 'message-sent');
    }
}
