<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;

class ContactEnquiryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ENQUIRIES LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $status = $request->query('status', 'all');

        if (!in_array($status, ['all', 'read', 'unread'], true)) {
            $status = 'all';
        }

        $query = ContactEnquiry::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($status === 'read') {
            $query->read();
        }

        if ($status === 'unread') {
            $query->unread();
        }

        $enquiries = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalCount = ContactEnquiry::count();

        $unreadCount = ContactEnquiry::unread()->count();

        $readCount = ContactEnquiry::read()->count();

        return view(
            'admin.contact-enquiries.index',
            compact(
                'enquiries',
                'totalCount',
                'unreadCount',
                'readCount',
                'search',
                'status'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ENQUIRY DETAILS
    |--------------------------------------------------------------------------
    */

    public function show(ContactEnquiry $enquiry)
    {
        if ($enquiry->read_at === null) {
            $enquiry->forceFill([
                'read_at' => now(),
            ])->save();
        }

        return view(
            'admin.contact-enquiries.show',
            compact('enquiry')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MARK AS UNREAD
    |--------------------------------------------------------------------------
    */

    public function markUnread(ContactEnquiry $enquiry)
    {
        $enquiry->forceFill([
            'read_at' => null,
        ])->save();

        return redirect()
            ->route('admin.contact-enquiries.index')
            ->with('success', 'Enquiry marked as unread.');
    }

    /*
    |--------------------------------------------------------------------------
    | MARK AS READ
    |--------------------------------------------------------------------------
    */

    public function markRead(ContactEnquiry $enquiry)
    {
        if ($enquiry->read_at === null) {
            $enquiry->forceFill([
                'read_at' => now(),
            ])->save();
        }

        return back()
            ->with('success', 'Enquiry marked as read.');
    }
}
