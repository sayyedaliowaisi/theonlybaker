<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RetailerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RetailerController extends Controller
{
    /**
     * Display retailer applications.
     */
    public function index(): View
    {
        $retailers = RetailerProfile::query()
            ->with('user:id,name,email,phone')
            ->latest()
            ->paginate(15);

        $counts = [
            'all' => RetailerProfile::count(),
            'pending' => RetailerProfile::where('status', 'pending')->count(),
            'approved' => RetailerProfile::where('status', 'approved')->count(),
            'rejected' => RetailerProfile::where('status', 'rejected')->count(),
        ];

        return view('admin.retailers.index', compact(
            'retailers',
            'counts'
        ));
    }

    /**
     * Display a retailer application.
     */
    public function show(RetailerProfile $retailerProfile): View
    {
        $retailerProfile->load('user');

        return view('admin.retailers.show', compact(
            'retailerProfile'
        ));
    }

    /**
     * Approve a pending retailer application.
     */
    public function approve(
        RetailerProfile $retailerProfile
    ): RedirectResponse {
        $updated = DB::transaction(function () use ($retailerProfile) {
            return RetailerProfile::query()
                ->whereKey($retailerProfile->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
        });

        if ($updated === 0) {
            return redirect()
                ->route('admin.retailers.show', $retailerProfile)
                ->with(
                    'error',
                    'Only pending retailer applications can be approved.'
                );
        }

        return redirect()
            ->route('admin.retailers.show', $retailerProfile)
            ->with(
                'success',
                'Retailer application approved successfully.'
            );
    }

    /**
     * Reject a pending retailer application.
     */
    public function reject(
        RetailerProfile $retailerProfile
    ): RedirectResponse {
        $updated = DB::transaction(function () use ($retailerProfile) {
            return RetailerProfile::query()
                ->whereKey($retailerProfile->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'approved_at' => null,
                ]);
        });

        if ($updated === 0) {
            return redirect()
                ->route('admin.retailers.show', $retailerProfile)
                ->with(
                    'error',
                    'Only pending retailer applications can be rejected.'
                );
        }

        return redirect()
            ->route('admin.retailers.show', $retailerProfile)
            ->with(
                'success',
                'Retailer application rejected successfully.'
            );
    }
}
