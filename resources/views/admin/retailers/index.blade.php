
@extends('layouts.admin')

@section('title', 'Retailer Applications')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:24px 16px 60px;">

    <div style="display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;margin-bottom:25px;">
        <div>
            <h1 style="font-size:28px;font-weight:700;margin:0 0 8px;">
                Retailer Applications
            </h1>
            <p style="color:#777;margin:0;">
                Manage wholesale retailer registrations.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div style="padding:14px;background:#eaf7ed;color:#216e39;border-radius:8px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:14px;background:#fff0ee;color:#a12622;border-radius:8px;margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:15px;margin-bottom:30px;">

        @foreach([
            'all' => 'Total Applications',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ] as $key => $label)

            <div style="background:#fff;border:1px solid #e8e1dc;border-radius:12px;padding:20px;">
                <div style="font-size:13px;color:#777;">
                    {{ $label }}
                </div>
                <div style="font-size:30px;font-weight:700;margin-top:8px;">
                    {{ $counts[$key] ?? 0 }}
                </div>
            </div>

        @endforeach

    </div>

    <div style="background:#fff;border:1px solid #e8e1dc;border-radius:12px;overflow:hidden;">

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead style="background:#f8f3ee;">
                    <tr>
                        <th style="padding:16px;">Business</th>
                        <th style="padding:16px;">Applicant</th>
                        <th style="padding:16px;">Phone</th>
                        <th style="padding:16px;">Status</th>
                        <th style="padding:16px;">Applied</th>
                        <th style="padding:16px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($retailers as $retailer)
                        <tr style="border-top:1px solid #eee;">
                            <td style="padding:16px;font-weight:600;">
                                {{ $retailer->business_name }}
                            </td>

                            <td style="padding:16px;">
                                <div>{{ $retailer->user?->name ?? 'N/A' }}</div>
                                <small style="color:#777;">
                                    {{ $retailer->user?->email ?? 'N/A' }}
                                </small>
                            </td>

                            <td style="padding:16px;">
                                {{ $retailer->business_phone }}
                            </td>

                            <td style="padding:16px;">
                                @php
                                    $statusColors = [
                                        'pending' => '#a66a15',
                                        'approved' => '#237747',
                                        'rejected' => '#ad3029',
                                    ];
                                    $statusColor = $statusColors[$retailer->status] ?? '#666';
                                @endphp

                                <span style="color:{{ $statusColor }};font-weight:700;">
                                    {{ ucfirst($retailer->status) }}
                                </span>
                            </td>

                            <td style="padding:16px;white-space:nowrap;">
                                {{ $retailer->created_at?->format('d M Y') }}
                            </td>

                            <td style="padding:16px;">
                                <a
                                    href="{{ route('admin.retailers.show', $retailer) }}"
                                    style="display:inline-block;background:#593728;color:#fff;padding:9px 14px;border-radius:7px;text-decoration:none;white-space:nowrap;"
                                >
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:35px;text-align:center;color:#777;">
                                No retailer applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div style="margin-top:22px;">
        {{ $retailers->links() }}
    </div>

</div>
@endsection
