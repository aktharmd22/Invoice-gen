<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ \App\Models\Setting::get('shop_name', 'Simple Billing') }}</title>
    @php $favicon = \App\Models\Setting::get('shop_favicon',''); @endphp
    @if($favicon)
        <link rel="icon" href="/storage/{{ $favicon }}" type="image/png">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'DM Sans', ui-sans-serif, system-ui; }</style>
</head>
<body class="min-h-screen bg-gray-950 flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-950 px-8 py-10 text-center">
                @php $loginLogo = \App\Models\Setting::get('shop_logo',''); @endphp
                @if($loginLogo)
                    <img src="/storage/{{ $loginLogo }}" alt="Logo"
                         class="h-14 w-auto object-contain mx-auto mb-4 rounded-xl">
                @else
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M15 7h.01M9 14h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                @endif
                <h1 class="text-xl font-bold text-white tracking-widest uppercase">
                    {{ \App\Models\Setting::get('shop_name', 'Simple Billing') }}
                </h1>
                <p class="text-gray-500 text-xs tracking-widest uppercase mt-1">Admin Portal</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                @if(session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all bg-gray-50"
                               placeholder="admin@billing.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all bg-gray-50"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-gray-900 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-xs text-gray-500">Remember me</label>
                    </div>

                    <button type="submit"
                            class="w-full bg-gray-950 hover:bg-black text-white py-3 px-4 rounded-xl font-semibold text-sm transition-all shadow-sm tracking-wide mt-2">
                        Sign In
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-gray-600 text-xs mt-5 tracking-wide">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('shop_name', 'Simple Billing') }}
        </p>
    </div>
</body>
</html>
