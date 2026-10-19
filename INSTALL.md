# The Only Baker — Saved Addresses Installation

1. Back up database and changed files.
2. Copy supplied files to matching project paths.
3. Add to `app/Models/User.php` before final `}`:
   ```php
   public function addresses(): HasMany { return $this->hasMany(\App\Models\CustomerAddress::class); }
   ```
4. In `routes/web.php`, ensure import `use App\Http\Controllers\Customer\AddressController;` and place these routes in an `auth` group (not `guest`):
   ```php
   Route::middleware('auth')->prefix('account/addresses')->name('account.addresses.')->group(function () {
       Route::get('/', [AddressController::class,'index'])->name('index');
       Route::post('/', [AddressController::class,'store'])->name('store');
       Route::put('/{address}', [AddressController::class,'update'])->name('update');
       Route::delete('/{address}', [AddressController::class,'destroy'])->name('destroy');
       Route::patch('/{address}/default', [AddressController::class,'makeDefault'])->name('default');
   });
   ```
5. Add link to dashboard/sidebar: `<a href="{{ route('account.addresses.index') }}">My Addresses</a>`.
6. In `CheckoutController@index`, before returning the view, define `$savedAddresses = $request->user()->addresses()->orderByDesc('is_default')->latest()->get();` and include `'savedAddresses'` in `compact(...)` or `->with('savedAddresses', $savedAddresses)`.
7. In checkout.blade.php, **inside** `<form id="tobCheckoutForm">`, immediately before the existing Delivery Address section, insert `@include('pages.partials.saved-addresses')`. After existing checkout.js script add `<script src="{{ asset('js/checkout-addresses.js') }}"></script>`.
8. In checkout.js payload add `save_address: formData.get('save_address') === '1', address_label: formData.get('address_label'), saved_address_id: formData.get('saved_address_choice') === 'new' ? null : formData.get('saved_address_choice')`.
9. IMPORTANT: CheckoutController@store must validate and persist save_address **only after a successful order**, not before. Use a transaction, and never trust a saved_address_id belonging to another user. If the checkout uses idempotency checkout_token, do not create duplicate addresses on retries. Exact placement depends on your existing 827-line CheckoutController. Ask for that file before changing order persistence. Until implemented, My Addresses CRUD works, checkout selection autofills, but checkout checkbox will NOT persist new addresses.
10. Run `php artisan migrate`, `php artisan optimize:clear`, `php artisan route:list --path=account/addresses -v`.

Order address should remain snapshotted in orders. Never link an order to mutable address details as its sole delivery record.
