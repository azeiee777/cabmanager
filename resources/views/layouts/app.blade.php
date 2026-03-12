<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CabManager Elite</title>

    <!-- Browser Tab Icons (Favicons) -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <!-- PWA / Mobile Home Screen Icons -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="CabManager">
    <meta name="theme-color" content="#0a0a0a">

    <!-- Link to Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="/icon.svg">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: {
                            950: '#0a0a0a',
                            900: '#171717',
                            800: '#262626'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 font-sans flex justify-center min-h-screen">

    <div class="w-full flex h-screen overflow-hidden bg-gray-950">

        <!-- DESKTOP SIDEBAR -->
        <aside class="hidden md:flex flex-col w-64 bg-gray-900 border-r border-gray-800 z-30 shadow-2xl">
            <div class="p-6 border-b border-gray-800 bg-black/20">
                <div class="flex items-center gap-3 mb-1">
                    <img src="/favicon.svg" alt="Logo" class="w-8 h-8">
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-amber-200 to-amber-500 bg-clip-text text-transparent">
                        CabManager</h1>
                </div>
                <p class="text-xs text-gray-400 font-medium tracking-wider cab-number-display">
                    {{ auth()->user()->cab_number ?? 'DL 1Z 9999' }}</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                <a href="{{ route('dashboard.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard.view') ? 'bg-amber-500/10 text-amber-400 font-bold' : 'text-gray-400 hover:bg-gray-800/50' }}">
                    <i data-lucide="home" class="w-5 h-5"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('ride.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('ride.view') ? 'bg-amber-500/10 text-amber-400 font-bold' : 'text-gray-400 hover:bg-gray-800/50' }}">
                    <i data-lucide="car" class="w-5 h-5"></i><span>Add Ride</span>
                </a>
                <a href="{{ route('expense.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('expense.view') ? 'bg-amber-500/10 text-amber-400 font-bold' : 'text-gray-400 hover:bg-gray-800/50' }}">
                    <i data-lucide="wallet" class="w-5 h-5"></i><span>Add Expense</span>
                </a>
                <a href="{{ route('tolls.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('tolls.view') ? 'bg-amber-500/10 text-amber-400 font-bold' : 'text-gray-400 hover:bg-gray-800/50' }}">
                    <i data-lucide="receipt" class="w-5 h-5"></i><span>Tolls Analysis</span>
                </a>
                <!-- New History Option -->
                <a href="{{ route('history.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('history.view') ? 'bg-amber-500/10 text-amber-400 font-bold' : 'text-gray-400 hover:bg-gray-800/50' }}">
                    <i data-lucide="history" class="w-5 h-5"></i><span>History</span>
                </a>
                <a href="{{ route('settings.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('settings.view') ? 'bg-amber-500/10 text-amber-400 font-bold' : 'text-gray-400 hover:bg-gray-800/50' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i><span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col h-full relative overflow-hidden bg-gray-950 md:bg-gray-950/50">
            <header
                class="md:hidden pt-8 pb-4 px-6 bg-gradient-to-b from-black to-gray-900 sticky top-0 z-20 border-b border-gray-800">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <img src="/favicon.svg" alt="Logo" class="w-10 h-10">
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-amber-200 to-amber-500 bg-clip-text text-transparent">
                                CabManager</h1>
                            <p class="text-xs text-gray-400 font-medium tracking-wider cab-number-display">
                                {{ auth()->user()->cab_number ?? 'DL 1Z 9999' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main
                class="flex-1 overflow-y-auto pb-24 md:pb-8 px-4 md:px-8 pt-4 md:pt-8 custom-scrollbar relative animate-in fade-in duration-300">
                <div class="max-w-4xl mx-auto w-full">
                    <div id="globalAlert"
                        class="hidden mb-6 p-4 rounded-xl flex items-center gap-3 transition-all shadow-lg">
                        <i id="globalAlertIcon" data-lucide="" class="w-6 h-6"></i>
                        <p id="globalAlertText" class="text-sm font-semibold"></p>
                    </div>
                    @yield('content')
                </div>
            </main>

            <!-- MOBILE BOTTOM NAV -->
            <nav
                class="md:hidden absolute bottom-0 w-full bg-gray-900/90 backdrop-blur-md border-t border-gray-800 pb-safe z-30 pt-2 pb-4 px-1 flex justify-between items-center">
                <a href="{{ route('dashboard.view') }}"
                    class="flex-1 flex flex-col items-center {{ request()->routeIs('dashboard.view') ? 'text-amber-400' : 'text-gray-500' }}">
                    <i data-lucide="home" class="w-5 h-5 mb-1"></i><span class="text-[10px]">Home</span>
                </a>
                <a href="{{ route('ride.view') }}"
                    class="flex-1 flex flex-col items-center {{ request()->routeIs('ride.view') ? 'text-amber-400' : 'text-gray-500' }}">
                    <i data-lucide="car" class="w-5 h-5 mb-1"></i><span class="text-[10px]">Ride</span>
                </a>
                <a href="{{ route('expense.view') }}"
                    class="flex-1 flex flex-col items-center {{ request()->routeIs('expense.view') ? 'text-amber-400' : 'text-gray-500' }}">
                    <i data-lucide="wallet" class="w-5 h-5 mb-1"></i><span class="text-[10px]">Expense</span>
                </a>
                <a href="{{ route('tolls.view') }}"
                    class="flex-1 flex flex-col items-center {{ request()->routeIs('tolls.view') ? 'text-amber-400' : 'text-gray-500' }}">
                    <i data-lucide="receipt" class="w-5 h-5 mb-1"></i><span class="text-[10px]">Tolls</span>
                </a>
                <!-- New History Option -->
                <a href="{{ route('history.view') }}"
                    class="flex-1 flex flex-col items-center {{ request()->routeIs('history.view') ? 'text-amber-400' : 'text-gray-500' }}">
                    <i data-lucide="history" class="w-5 h-5 mb-1"></i><span class="text-[10px]">History</span>
                </a>
                <a href="{{ route('settings.view') }}"
                    class="flex-1 flex flex-col items-center {{ request()->routeIs('settings.view') ? 'text-amber-400' : 'text-gray-500' }}">
                    <i data-lucide="settings" class="w-5 h-5 mb-1"></i><span class="text-[10px]">Settings</span>
                </a>
            </nav>
        </div>
    </div>

    <script>
        lucide.createIcons();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showAlert(message, type = 'success') {
            const box = $('#globalAlert');
            box.removeClass(
                'hidden bg-emerald-500/10 border-emerald-500/20 text-emerald-400 bg-rose-500/10 border-rose-500/20 text-rose-400 border'
                );
            $('#globalAlertIcon').attr('data-lucide', type === 'success' ? 'check-circle-2' : 'alert-circle');
            box.addClass(type === 'success' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' :
                'bg-rose-500/10 border-rose-500/20 text-rose-400').removeClass('hidden');
            $('#globalAlertText').text(message);
            lucide.createIcons();
            setTimeout(() => box.addClass('hidden'), 3000);
        }
    </script>
    @stack('scripts')
</body>

</html>
