<?php

namespace App\Http\Controllers;

use App\Models\RetailerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\View\View;

class RetailerRegistrationController extends Controller
{
    /**
     * Show retailer registration form.
     */
    public function create(Request $request): View
    {
        $profile = $request->user()->retailerProfile;

        return view('retailer.register', [
            'profile' => $profile,
        ]);
    }

    /**
     * Submit retailer application.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Prevent duplicate applications.
        if ($user->retailerProfile()->exists()) {
            return redirect()
                ->route('retailer.register')
                ->with(
                    'retailer_info',
                    'You have already submitted a retailer application.'
                );
        }

        $validated = $request->validate([
            'business_name' => [
                'required',
                'string',
                'max:255',
            ],
            'business_phone' => [
                'required',
                'string',
                'max:20',
            ],
            'business_address' => [
                'required',
                'string',
                'max:2000',
            ],
            'gst_number' => [
    'nullable',
    'string',
    'size:15',
    'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/i',
],
        ]);

        if (!empty($validated['gst_number'])) {
            $validated['gst_number'] = strtoupper(
                $validated['gst_number']
            );
        }

        DB::transaction(function () use ($user, $validated) {
            RetailerProfile::create([
                'user_id' => $user->id,
                'business_name' => $validated['business_name'],
                'business_phone' => $validated['business_phone'],
                'business_address' => $validated['business_address'],
                'gst_number' => $validated['gst_number'] ?? null,
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('retailer.register')
            ->with(
                'retailer_success',
                'Your retailer application has been submitted successfully. Please wait for admin approval.'
            );
    }
}
