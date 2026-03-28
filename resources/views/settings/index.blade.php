@extends('layouts.app')
@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Shop & Invoice Settings</h2>
        </div>

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            {{-- Logo + Favicon --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Shop Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shop Logo</label>
                    <div class="flex items-start gap-4">
                        @if($shopLogo)
                            <div class="w-16 h-16 border border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <img src="/storage/{{ $shopLogo }}" alt="Shop Logo" class="max-w-full max-h-full object-contain">
                            </div>
                        @else
                            <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-300 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <input type="file" name="shop_logo" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-700 cursor-pointer">
                            <p class="mt-1.5 text-xs text-gray-400">PNG, JPG, SVG up to 2MB.<br>Used in sidebar &amp; invoice.</p>
                        </div>
                    </div>
                </div>

                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="flex items-start gap-4">
                        @if($shopFavicon)
                            <div class="w-16 h-16 border border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <img src="/storage/{{ $shopFavicon }}" alt="Favicon" class="w-8 h-8 object-contain">
                            </div>
                        @else
                            <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-300 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <input type="file" name="shop_favicon" accept="image/png,image/x-icon,image/svg+xml,image/webp"
                                   class="block w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-700 cursor-pointer">
                            <p class="mt-1.5 text-xs text-gray-400">PNG, ICO, SVG up to 512KB.<br>Shown in browser tab.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="border-t border-gray-100 pt-6 space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Name <span class="text-red-500">*</span></label>
                        <input type="text" name="shop_name" value="{{ old('shop_name', $shopName) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                               placeholder="e.g. MR BLACK">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number(s)</label>
                        <input type="text" name="shop_phone" value="{{ old('shop_phone', $shopPhone) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                               placeholder="e.g. 8122244387 | 8438904298">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Address</label>
                    <textarea name="shop_address" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 resize-none"
                              placeholder="Palluruthy Nada, Palluruthy, Kochi, Kerala 682006">{{ old('shop_address', $shopAddress) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Google Maps URL</label>
                    <input type="url" name="shop_maps_url" value="{{ old('shop_maps_url', $shopMapsUrl) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                           placeholder="https://maps.app.goo.gl/...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Instagram URL</label>
                    <input type="url" name="shop_instagram" value="{{ old('shop_instagram', $shopInsta) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                           placeholder="https://www.instagram.com/...">
                </div>

                <div class="sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Return Window (days) <span class="text-red-500">*</span></label>
                    <input type="number" name="return_days" value="{{ old('return_days', $returnDays) }}" min="1" max="365" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-8 py-2.5 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    {{-- Password Change --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Change Password</h2>
        </div>
        <form method="POST" action="{{ route('settings.password') }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required
                       class="w-full px-4 py-2.5 border {{ $errors->password->has('current_password') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                       placeholder="Enter current password">
                @error('current_password', 'password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 border {{ $errors->password->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                           placeholder="Min. 8 characters">
                    @error('password', 'password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                           placeholder="Repeat new password">
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="submit"
                        class="px-8 py-2.5 bg-gray-900 hover:bg-black text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
