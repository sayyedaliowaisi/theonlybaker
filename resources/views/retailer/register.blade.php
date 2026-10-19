
@extends('layouts.app')

@section('title', 'Become a Retailer | The Only Baker')

@push('styles')
<style>
    .tob-retailer-page {
        min-height: 75vh;
        padding: 65px 20px 90px;
        background: #fffaf5;
        color: #39251e;
    }

    .tob-retailer-container {
        max-width: 760px;
        margin: 0 auto;
    }

    .tob-retailer-heading {
        text-align: center;
        margin-bottom: 35px;
    }

    .tob-retailer-heading span {
        display: block;
        color: #a46c47;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .tob-retailer-heading h1 {
        font-size: clamp(30px, 5vw, 46px);
        line-height: 1.2;
        margin: 0 0 14px;
        font-family: Georgia, serif;
    }

    .tob-retailer-heading p {
        color: #77655c;
        line-height: 1.8;
        max-width: 550px;
        margin: 0 auto;
    }

    .tob-retailer-card {
        background: #fff;
        padding: 38px;
        border: 1px solid #eee0d3;
        border-radius: 18px;
        box-shadow: 0 12px 40px rgba(70, 40, 20, .06);
    }

    .tob-retailer-card h2 {
        font-size: 24px;
        font-family: Georgia, serif;
        margin: 0 0 22px;
    }

    .tob-retailer-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .tob-retailer-field {
        margin-bottom: 20px;
    }

    .tob-retailer-field label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .tob-retailer-field input,
    .tob-retailer-field textarea {
        width: 100%;
        padding: 13px 15px;
        border: 1px solid #ded0c4;
        border-radius: 9px;
        font: inherit;
        font-size: 14px;
        background: #fff;
        color: #39251e;
        box-sizing: border-box;
    }

    .tob-retailer-field input:focus,
    .tob-retailer-field textarea:focus {
        outline: 2px solid #b98967;
        outline-offset: 1px;
    }

    .tob-retailer-field textarea {
        resize: vertical;
        min-height: 110px;
    }

    .tob-retailer-field small {
        display: block;
        margin-top: 6px;
        color: #827269;
        font-size: 12px;
    }

    .tob-retailer-error {
        color: #b42318;
        font-size: 12px;
        margin-top: 6px;
    }

    .tob-retailer-alert {
        padding: 15px 18px;
        border-radius: 9px;
        margin-bottom: 22px;
        font-size: 14px;
        line-height: 1.7;
    }

    .tob-retailer-alert-success {
        background: #edf8ef;
        border: 1px solid #b9dec0;
        color: #245b31;
    }

    .tob-retailer-alert-info {
        background: #fff5e6;
        border: 1px solid #efd2a3;
        color: #795019;
    }

    .tob-retailer-alert-error {
        background: #fff0ee;
        border: 1px solid #efc3bc;
        color: #9c3025;
    }

    .tob-retailer-button {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 15px 22px;
        background: #593728;
        color: #fff;
        border: 0;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        cursor: pointer;
        transition: background .2s;
    }

    .tob-retailer-button:hover {
        background: #794b35;
    }

    .tob-retailer-status {
        text-align: center;
    }

    .tob-retailer-status-icon {
        font-size: 42px;
        margin-bottom: 15px;
    }

    .tob-retailer-status p {
        color: #77655c;
        line-height: 1.8;
    }

    .tob-retailer-business {
        background: #fffaf5;
        border: 1px solid #eee0d3;
        border-radius: 10px;
        padding: 18px;
        margin-top: 25px;
        text-align: left;
        overflow-wrap: anywhere;
    }

    .tob-retailer-business p {
        margin: 7px 0;
        font-size: 14px;
    }

    @media (max-width: 640px) {
        .tob-retailer-page {
            padding: 40px 15px 65px;
        }

        .tob-retailer-card {
            padding: 23px 18px;
        }

        .tob-retailer-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>
@endpush

@section('content')

<section class="tob-retailer-page">
    <div class="tob-retailer-container">

        <div class="tob-retailer-heading">
            <span>THE ONLY BAKER WHOLESALE</span>

            <h1>Become a Retail Partner</h1>

            <p>
                Partner with The Only Baker and bring our
                handcrafted cookies to your customers.
                Apply for a wholesale account today.
            </p>
        </div>

        @if(session('retailer_success'))
            <div class="tob-retailer-alert tob-retailer-alert-success" role="status">
                {{ session('retailer_success') }}
            </div>
        @endif

        @if(session('retailer_info'))
            <div class="tob-retailer-alert tob-retailer-alert-info" role="status">
                {{ session('retailer_info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="tob-retailer-alert tob-retailer-alert-error" role="alert">
                Please correct the highlighted fields and try again.
            </div>
        @endif

        <div class="tob-retailer-card">

            @if($profile)

                <div class="tob-retailer-status">

                    @if($profile->isPending())

                        <div class="tob-retailer-status-icon">⏳</div>

                        <h2>Application Under Review</h2>

                        <p>
                            Your retailer application has been received.
                            Our team will review your business details.
                            Wholesale pricing will become available
                            after approval.
                        </p>

                    @elseif($profile->isApproved())

                        <div class="tob-retailer-status-icon">✓</div>

                        <h2>Retailer Account Approved</h2>

                        <p>
                            Congratulations! Your retailer account
                            has been approved.
                        </p>

                        <p>
                            Wholesale product pricing and bulk ordering
                            will be available as those features are enabled.
                        </p>

                    @elseif($profile->isRejected())

                        <div class="tob-retailer-status-icon">!</div>

                        <h2>Application Not Approved</h2>

                        <p>
                            Your retailer application was not approved.
                            Please contact our support team
                            for further assistance.
                        </p>

                    @else

                        <h2>Application Status</h2>

                        <p>
                            Your application status is currently unavailable.
                            Please contact support.
                        </p>

                    @endif

                    <div class="tob-retailer-business">

                        <p>
                            <strong>Business:</strong>
                            {{ $profile->business_name }}
                        </p>

                        <p>
                            <strong>Business Phone:</strong>
                            {{ $profile->business_phone }}
                        </p>

                        <p>
                            <strong>Application Status:</strong>
                            {{ ucfirst($profile->status) }}
                        </p>

                        <p>
                            <strong>Applied On:</strong>
                            {{ $profile->created_at?->format('d M Y') }}
                        </p>

                    </div>

                </div>

            @else

                <h2>Retailer Registration</h2>

                <form
                    action="{{ route('retailer.store') }}"
                    method="POST"
                >
                    @csrf

                    <div class="tob-retailer-grid">

                        <div class="tob-retailer-field">
                            <label for="business_name">
                                Business / Shop Name *
                            </label>

                            <input
                                type="text"
                                id="business_name"
                                name="business_name"
                                value="{{ old('business_name') }}"
                                maxlength="255"
                                autocomplete="organization"
                                required
                            >

                            @error('business_name')
                                <div class="tob-retailer-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="tob-retailer-field">
                            <label for="business_phone">
                                Business Phone *
                            </label>

                            <input
                                type="tel"
                                id="business_phone"
                                name="business_phone"
                                value="{{ old('business_phone') }}"
                                maxlength="20"
                                autocomplete="tel"
                                required
                            >

                            @error('business_phone')
                                <div class="tob-retailer-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="tob-retailer-field">
                        <label for="business_address">
                            Business Address *
                        </label>

                        <textarea
                            id="business_address"
                            name="business_address"
                            maxlength="2000"
                            autocomplete="street-address"
                            required
                        >{{ old('business_address') }}</textarea>

                        @error('business_address')
                            <div class="tob-retailer-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="tob-retailer-field">
                        <label for="gst_number">
                            GST Number (Optional)
                        </label>

                        <input
                            type="text"
                            id="gst_number"
                            name="gst_number"
                            value="{{ old('gst_number') }}"
                            maxlength="15"
                            style="text-transform: uppercase"
                            placeholder="Example: 09ABCDE1234F1Z5"
                        >

                        <small>
                            Enter your 15-character GSTIN if applicable.
                        </small>

                        @error('gst_number')
                            <div class="tob-retailer-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="tob-retailer-button"
                    >
                        SUBMIT RETAILER APPLICATION
                    </button>

                </form>

            @endif

        </div>

    </div>
</section>

@endsection
