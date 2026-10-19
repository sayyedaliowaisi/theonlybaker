<?php

namespace App\Http\Controllers;

use App\Mail\ContactEnquiryReceived;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],

            'subject' => [
                'required',
                Rule::in([
                    'general',
                    'product',
                    'order',
                    'gifting',
                    'bulk',
                    'other',
                ]),
            ],

            'message' => ['required', 'string', 'max:3000'],
        ]);

        // Save enquiry first.
        $enquiry = ContactEnquiry::create($validated);

        try {
            Mail::to(
                config('mail.contact_notification_email')
            )->send(
                new ContactEnquiryReceived($enquiry)
            );
        } catch (Throwable $exception) {
            Log::error('Contact enquiry email failed', [
                'enquiry_id' => $enquiry->id,
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('contact')
                ->with(
                    'success',
                    'Your enquiry has been received. Our team will review it shortly.'
                );
        }

        return redirect()
            ->route('contact')
            ->with(
                'success',
                'Thank you! Your message has been received successfully.'
            );
    }
}
