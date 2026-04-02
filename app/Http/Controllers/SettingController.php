<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'shopName'      => Setting::get('shop_name',      'My Shop'),
            'shopPhone'     => Setting::get('shop_phone',     ''),
            'shopAddress'   => Setting::get('shop_address',   ''),
            'shopMapsUrl'   => Setting::get('shop_maps_url',  ''),
            'shopInsta'     => Setting::get('shop_instagram', ''),
            'shopLogo'      => Setting::get('shop_logo',      ''),
            'shopFavicon'   => Setting::get('shop_favicon',   ''),
            'returnDays'    => Setting::get('return_days',    '7'),
            'upiId'         => Setting::get('upi_id',         ''),
            'upiQrEnabled'  => Setting::get('upi_qr_enabled', '0'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name'     => 'required|string|max:255',
            'shop_phone'    => 'nullable|string|max:100',
            'shop_address'  => 'nullable|string|max:500',
            'shop_maps_url' => 'nullable|url|max:500',
            'shop_instagram'=> 'nullable|url|max:500',
            'return_days'   => 'required|integer|min:1|max:365',
            'shop_logo'     => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'shop_favicon'  => 'nullable|image|mimes:png,ico,svg,webp|max:512',
        ]);

        Setting::set('shop_name',      $request->shop_name);
        Setting::set('shop_phone',     $request->shop_phone ?? '');
        Setting::set('shop_address',   $request->shop_address ?? '');
        Setting::set('shop_maps_url',  $request->shop_maps_url ?? '');
        Setting::set('shop_instagram', $request->shop_instagram ?? '');
        Setting::set('return_days',    $request->return_days);
        Setting::set('upi_id',         $request->upi_id ?? '');
        Setting::set('upi_qr_enabled', $request->has('upi_qr_enabled') ? '1' : '0');

        if ($request->hasFile('shop_logo')) {
            $oldLogo = Setting::get('shop_logo', '');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('shop_logo')->store('logos', 'public');
            Setting::set('shop_logo', $path);
        }

        if ($request->hasFile('shop_favicon')) {
            $oldFavicon = Setting::get('shop_favicon', '');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $path = $request->file('shop_favicon')->store('favicons', 'public');
            Setting::set('shop_favicon', $path);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validateWithBag('password', [
            'current_password'  => 'required',
            'password'          => 'required|min:8|confirmed',
        ]);

        $admin = auth()->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'], 'password');
        }

        $admin->update(['password' => Hash::make($request->password)]);

        return redirect()->route('settings.index')
            ->with('success', 'Password updated successfully.');
    }
}
