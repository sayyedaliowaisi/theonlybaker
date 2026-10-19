<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AddressController extends Controller {
    public function index(Request $request) {
        $addresses = $request->user()->addresses()->orderByDesc('is_default')->latest()->get();
        return view('customer.account.addresses', compact('addresses'));
    }
    private function rules(): array {
        return [
            'label'=>['required','string','max:40'],
            'name'=>['required','string','max:100'],
            'phone'=>['required','string','max:20'],
            'address'=>['required','string','max:500'],
            'landmark'=>['nullable','string','max:150'],
            'city'=>['required','string','max:100'],
            'state'=>['required','string','max:100'],
            'pincode'=>['required','regex:/^[0-9]{6}$/'],
            'is_default'=>['sometimes','boolean'],
        ];
    }
    public function store(Request $request) {
        $data=$request->validate($this->rules());
        DB::transaction(function () use ($request,$data) {
            $user=$request->user();
            $user->addresses()->lockForUpdate()->get();
            $makeDefault=$user->addresses()->count()===0 || (bool)($data['is_default']??false);
            if ($makeDefault) $user->addresses()->update(['is_default'=>false]);
            unset($data['is_default']);
            $user->addresses()->create($data+['is_default'=>$makeDefault]);
        });
        return redirect()->route('account.addresses.index')->with('address_success','Address added successfully.');
    }
    public function update(Request $request, CustomerAddress $address) {
        abort_unless($address->user_id===$request->user()->id,404);
        $data=$request->validate($this->rules());
        DB::transaction(function () use ($request,$address,$data) {
            $request->user()->addresses()->lockForUpdate()->get();
            if (!empty($data['is_default'])) $request->user()->addresses()->update(['is_default'=>false]);
            $isDefault=$address->is_default || !empty($data['is_default']);
            unset($data['is_default']);
            $address->update($data+['is_default'=>$isDefault]);
        });
        return back()->with('address_success','Address updated successfully.');
    }
    public function destroy(Request $request, CustomerAddress $address) {
        abort_unless($address->user_id===$request->user()->id,404);
        DB::transaction(function () use ($request,$address) {
            $request->user()->addresses()->lockForUpdate()->get();
            $wasDefault=$address->is_default;
            $address->delete();
            if ($wasDefault) {
                $next=$request->user()->addresses()->oldest()->first();
                if ($next) $next->update(['is_default'=>true]);
            }
        });
        return back()->with('address_success','Address deleted successfully.');
    }
    public function makeDefault(Request $request, CustomerAddress $address) {
        abort_unless($address->user_id===$request->user()->id,404);
        DB::transaction(function () use ($request,$address) {
            $request->user()->addresses()->lockForUpdate()->get();
            $request->user()->addresses()->update(['is_default'=>false]);
            $address->update(['is_default'=>true]);
        });
        return back()->with('address_success','Default address updated.');
    }
}
