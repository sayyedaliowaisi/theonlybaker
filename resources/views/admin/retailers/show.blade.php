
@extends('layouts.admin')

@section('title', 'Retailer Application Details')

@section('content')
<div style="max-width:850px;margin:0 auto;padding:24px 16px 60px;">

    <a
        href="{{ route('admin.retailers.index') }}"
        style="color:#593728;text-decoration:none;display:inline-block;margin-bottom:22px;"
    >
        ← Back to Retailer Applications
    </a>

    <h1 style="font-size:28px;font-weight:700;margin-bottom:25px;">
        Retailer Application Details
    </h1>

    @if(session('success'))
        <div style="padding:15px;background:#eaf7ed;color:#216e39;border-radius:8px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:15px;background:#fff0ee;color:#a12622;border-radius:8px;margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background:#fff;border:1px solid #e8e1dc;border-radius:14px;padding:28px;">

        <h2 style="font-size:23px;margin:0 0 20px;">
            {{ $retailerProfile->business_name }}
        </h2>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:22px;">

            <div>
                <strong>Applicant Name</strong>
                <p>{{ $retailerProfile->user?->name ?? 'N/A' }}</p>
            </div>

            <div>
                <strong>Email Address</strong>
                <p style="overflow-wrap:anywhere;">
                    {{ $retailerProfile->user?->email ?? 'N/A' }}
                </p>
            </div>

            <div>
                <strong>Business Phone</strong>
                <p>{{ $retailerProfile->business_phone }}</p>
            </div>

            <div>
                <strong>GST Number</strong>
                <p>{{ $retailerProfile->gst_number ?: 'Not provided' }}</p>
            </div>

            <div>
                <strong>Application Date</strong>
                <p>{{ $retailerProfile->created_at?->format('d M Y, h:i A') }}</p>
            </div>

            <div>
                <strong>Application Status</strong>
                <p style="font-weight:700;">
                    {{ ucfirst($retailerProfile->status) }}
                </p>
            </div>

        </div>

        <div style="margin-top:20px;">
            <strong>Business Address</strong>
            <p style="white-space:pre-line;overflow-wrap:anywhere;">{{ $retailerProfile->business_address }}</p>
        </div>

        @if($retailerProfile->isApproved() && $retailerProfile->approved_at)
            <div style="margin-top:20px;">
                <strong>Approved On</strong>
                <p>{{ $retailerProfile->approved_at->format('d M Y, h:i A') }}</p>
            </div>
        @endif

        @if($retailerProfile->isPending())

            <div style="border-top:1px solid #eee;margin-top:30px;padding-top:25px;">

                <h3 style="font-size:18px;margin:0 0 15px;">
                    Review Application
                </h3>

                <p style="color:#777;font-size:14px;margin-bottom:20px;">
                    Please verify the business details before approving.
                </p>

                <div style="display:flex;gap:12px;flex-wrap:wrap;">

                    <form
                        action="{{ route('admin.retailers.approve', $retailerProfile) }}"
                        method="POST"
                        onsubmit="return confirm('Approve this retailer application?')"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            style="background:#237747;color:white;border:0;border-radius:8px;padding:13px 24px;font-weight:700;cursor:pointer;"
                        >
                            Approve Retailer
                        </button>
                    </form>

                    <form
                        action="{{ route('admin.retailers.reject', $retailerProfile) }}"
                        method="POST"
                        onsubmit="return confirm('Reject this retailer application?')"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            style="background:#ad3029;color:white;border:0;border-radius:8px;padding:13px 24px;font-weight:700;cursor:pointer;"
                        >
                            Reject Application
                        </button>
                    </form>

                </div>
            </div>

        @else

            <div style="background:#f8f3ee;padding:16px;border-radius:9px;margin-top:25px;">
                This application has already been reviewed.
                Its status cannot be changed using these actions.
            </div>

        @endif

    </div>

</div>
@endsection
