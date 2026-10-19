
@php
    $savedAddresses = $savedAddresses ?? collect();
    $defaultAddress = $savedAddresses->firstWhere('is_default', true) ?? $savedAddresses->first();
@endphp

<div class="tob-saved-addresses-section" id="tobSavedAddresses">
    @if($savedAddresses->isNotEmpty())
        <div class="tob-saved-addresses-heading">
            <h3>Choose a saved address</h3>
            <a href="{{ route('account.addresses.index') }}">Manage Addresses →</a>
        </div>
        <div class="tob-saved-addresses-list">
            @foreach($savedAddresses as $savedAddress)
                @php
                    $addressData = [
                        'id' => $savedAddress->id,
                        'name' => $savedAddress->name,
                        'phone' => $savedAddress->phone,
                        'address' => $savedAddress->address,
                        'landmark' => $savedAddress->landmark,
                        'city' => $savedAddress->city,
                        'state' => $savedAddress->state,
                        'pincode' => $savedAddress->pincode,
                    ];
                @endphp
                <label class="tob-saved-address-option">
                    <input type="radio" name="saved_address_id" value="{{ $savedAddress->id }}"
                        data-saved-address="{{ json_encode($addressData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}"
                        @checked((string) old('saved_address_id', $defaultAddress?->id) === (string) $savedAddress->id)>
                    <span class="tob-saved-address-body">
                        <span class="tob-saved-address-top"><strong>{{ $savedAddress->label ?: 'Address' }}</strong>
                            @if($savedAddress->is_default)<span class="tob-saved-address-badge">Default</span>@endif
                        </span>
                        <span class="tob-saved-address-name">{{ $savedAddress->name }} · {{ $savedAddress->phone }}</span>
                        <span class="tob-saved-address-line">{{ $savedAddress->address }}@if($savedAddress->landmark), {{ $savedAddress->landmark }}@endif</span>
                        <span class="tob-saved-address-line">{{ $savedAddress->city }}, {{ $savedAddress->state }} - {{ $savedAddress->pincode }}</span>
                    </span>
                </label>
            @endforeach
            <label class="tob-saved-address-option tob-saved-address-new">
                <input type="radio" name="saved_address_id" value="new" @checked(old('saved_address_id') === 'new')>
                <span class="tob-saved-address-body"><strong>+ Use a new address</strong><small>Enter another delivery location below</small></span>
            </label>
        </div>
    @else
        <input type="hidden" name="saved_address_id" value="new">
        <p class="tob-saved-address-empty">Add a delivery address below. You can save it for your next order.</p>
    @endif

    <div class="tob-save-address-options" id="tobSaveAddressOptions">
        <label class="tob-save-address-checkbox" for="tobSaveAddress">
            <input type="checkbox" name="save_address" id="tobSaveAddress" value="1" @checked(old('save_address'))>
            <span>Save this delivery address for future orders</span>
        </label>
        <div class="tob-save-address-label" id="tobAddressLabelWrap" hidden>
            <label for="tobAddressLabel">Address Label</label>
            <input type="text" id="tobAddressLabel" name="address_label" value="{{ old('address_label', 'Home') }}" placeholder="Home, Office, Other" maxlength="50">
        </div>
    </div>
</div>
