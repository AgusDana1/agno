<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->latest()->get();
        return view('user.addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => [
                'required',
                'string',
                'max:50'
            ],
            'recipient_name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'max:30'
            ],
            'address' => [
                'required',
                'string',
                'max:100'
            ],
            'province' => [
                'required',
                'string',
                'max:100'
            ],
            'city' => [
                'required',
                'string',
                'max:100'
            ],
            'district' => [
                'required',
                'string',
                'max:100'
            ],
            'postal_code' => [
                'required',
                'string',
                'max:20'
            ],
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90'
            ],
            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180'
            ],
            'is_default' => [
                'nullable',
                'boolean'
            ],
        ]);

        DB::transaction(function () use ($request, $validated) {
            if ($request->boolean('is_default')) {
                $request->user()->addresses()->update(['is_default' => false]);
            }

            $request->user()->addresses()->create([...$validated, 'is_default' => $request->boolean('is_default')]);
        });

        return back()->with('success', 'Alamat berhasil disimpan');
    }

    public function update(Request $request, Address $address) 
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string','max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $address, $validated) {
            if ($request->boolean('is_default')) {
                $request->user()->addresses()->whereKeyNot($address->id)->update(['is_default' => false]);
            }

            $address->update([...$validated, 'is_default' => $request->boolean('is_default'), 
            ]);
        });

        return back()->with(
            'success',
            'Alamat berhasil diperbaharui'
        );
    }

    public function destroy(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $address->delete();

        return back()->with(
            'success',
            'Alamat berhasil dihapus'
        );
    }
}
