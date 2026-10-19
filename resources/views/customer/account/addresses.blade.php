
@extends('layouts.app')

@section('title', 'My Addresses | The Only Baker')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account-addresses.css') }}?v=3">
@endpush

@section('content')
<main class="tob-address-page">
    <div class="tob-address-container">
        <a class="tob-address-back" href="{{ route('account.dashboard') }}">← Back to My Account</a>
        <div class="tob-address-header">
            <span class="tob-address-eyebrow">MY ACCOUNT</span>
            <h1>My Addresses</h1>
            <p>Manage your delivery addresses for a faster checkout.</p>
        </div>

        @if(session('address_success'))
            <div class="tob-address-alert tob-address-alert-success" role="status">{{ session('address_success') }}</div>
        @endif
        @if($errors->any())
            <div class="tob-address-alert tob-address-alert-error" role="alert">
                <strong>Please correct the following:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($addresses->isNotEmpty())
            <h2 class="tob-address-section-title">Saved Addresses <span>({{ $addresses->count() }})</span></h2>
            <div class="tob-address-grid">
                @foreach($addresses as $address)
                    <article class="tob-address-card {{ $address->is_default ? 'is-default' : '' }}">
                        <div class="tob-address-card-head">
                            <h3>{{ $address->label ?: 'Address' }}</h3>
                            @if($address->is_default)
                                <span class="tob-address-default-badge">Default</span>
                            @endif
                        </div>
                        <p class="tob-address-person">{{ $address->name }}</p>
                        <p class="tob-address-muted">{{ $address->phone }}</p>
                        <p class="tob-address-location">
                            {{ $address->address }}
                            @if($address->landmark)<br>{{ $address->landmark }}@endif
                            <br>{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                        </p>
                        <div class="tob-address-actions">
                            @unless($address->is_default)
                                <form method="POST" action="{{ route('account.addresses.default', $address) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="tob-address-btn tob-address-btn-outline">Make Default</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" onsubmit="return confirm('Delete this address?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tob-address-btn tob-address-btn-danger">Delete</button>
                            </form>
                        </div>
                        <details class="tob-address-edit">
                            <summary>Edit Address <span aria-hidden="true">⌄</span></summary>
                            <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="tob-address-form">
                                @csrf
                                @method('PUT')
                                @foreach(['label' => 'Label (Home / Office)', 'name' => 'Full Name', 'phone' => 'Phone', 'address' => 'House / Street', 'landmark' => 'Landmark', 'city' => 'City', 'state' => 'State', 'pincode' => 'PIN Code'] as $field => $caption)
                                    <div class="tob-address-field {{ $field === 'address' ? 'tob-address-full' : '' }}">
                                        <label for="edit-{{ $address->id }}-{{ $field }}">{{ $caption }} @if($field !== 'landmark')<span>*</span>@endif</label>
                                        <input
                                            id="edit-{{ $address->id }}-{{ $field }}"
                                            name="{{ $field }}"
                                            type="{{ $field === 'phone' ? 'tel' : 'text' }}"
                                            value="{{ $address->{$field} }}"
                                            maxlength="{{ $field === 'address' ? 500 : ($field === 'landmark' ? 150 : ($field === 'label' ? 50 : 100)) }}"
                                            @if($field === 'pincode') pattern="[0-9]{6}" inputmode="numeric" maxlength="6" @endif
                                            @if($field !== 'landmark') required @endif
                                        >
                                    </div>
                                @endforeach
                                <label class="tob-address-checkbox tob-address-full">
                                    <input type="checkbox" name="is_default" value="1" @checked($address->is_default)>
                                    Set as default address
                                </label>
                                <div class="tob-address-full"><button class="tob-address-btn tob-address-btn-primary" type="submit">Save Changes</button></div>
                            </form>
                        </details>
                    </article>
                @endforeach
            </div>
        @else
            <div class="tob-address-empty">No saved addresses yet. Add your first delivery address below.</div>
        @endif

        <section class="tob-address-add" aria-labelledby="tob-add-address-title">
            <div class="tob-address-add-heading">
                <span class="tob-address-add-icon">+</span>
                <div>
                    <h2 id="tob-add-address-title">Add New Address</h2>
                    <p>Save a home, office, or another delivery location.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('account.addresses.store') }}" class="tob-address-form">
                @csrf
                @foreach(['label' => 'Label (Home / Office)', 'name' => 'Full Name', 'phone' => 'Phone', 'address' => 'House / Street', 'landmark' => 'Landmark', 'city' => 'City', 'state' => 'State', 'pincode' => 'PIN Code'] as $field => $caption)
                    <div class="tob-address-field {{ $field === 'address' ? 'tob-address-full' : '' }}">
                        <label for="new-{{ $field }}">{{ $caption }} @if($field !== 'landmark')<span>*</span>@endif</label>
                        <input
                            id="new-{{ $field }}"
                            name="{{ $field }}"
                            type="{{ $field === 'phone' ? 'tel' : 'text' }}"
                            value="{{ old($field) }}"
                            placeholder="{{ $field === 'label' ? 'Home / Office' : ($field === 'address' ? 'House / Flat No., Building, Street' : $caption) }}"
                            maxlength="{{ $field === 'address' ? 500 : ($field === 'landmark' ? 150 : ($field === 'label' ? 50 : 100)) }}"
                            @if($field === 'pincode') pattern="[0-9]{6}" inputmode="numeric" maxlength="6" @endif
                            @if($field !== 'landmark') required @endif
                        >
                    </div>
                @endforeach
                <label class="tob-address-checkbox tob-address-full">
                    <input type="checkbox" name="is_default" value="1" @checked(old('is_default'))>
                    Set as default delivery address
                </label>
                <div class="tob-address-full"><button type="submit" class="tob-address-btn tob-address-btn-primary">+ Add Address</button></div>
            </form>
        </section>
    </div>
</main>
@endsection
