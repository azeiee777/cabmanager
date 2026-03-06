<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CabManager Elite</title>
    <!-- Tailwind CSS -->
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
    <!-- jQuery -->
    <script src="[https://code.jquery.com/jquery-3.7.1.min.js](https://code.jquery.com/jquery-3.7.1.min.js)"></script>
    <!-- Lucide Icons -->
    <script src="[https://unpkg.com/lucide@latest](https://unpkg.com/lucide@latest)"></script>

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

        .animate-drive {
            animation: driveCar 4s linear infinite;
        }

        @keyframes driveCar {
            0% {
                transform: translateX(-60px);
            }

            100% {
                transform: translateX(120vw);
            }
        }

        /* Hide sections by default */
        .spa-section {
            display: none;
        }

        .active-section {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 font-sans flex justify-center min-h-screen">

    <div class="w-full max-w-md bg-gray-900 shadow-2xl relative overflow-hidden flex flex-col h-screen">

        <!-- LOADING OVERLAY -->
        <div id="loadingOverlay" class="fixed inset-0 z-50 bg-gray-950 flex flex-col items-center justify-center">
            <i data-lucide="loader-2" class="animate-spin text-amber-500 w-10 h-10 mb-4"></i>
            <p class="text-gray-400 font-medium">Loading CabManager...</p>
        </div>

        <!-- LANDING & AUTH SECTION -->
        <div id="landingView"
            class="spa-section flex flex-col h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-900 via-gray-950 to-black overflow-y-auto">
            <div class="p-6 pt-12 relative z-10">
                <h2 class="text-3xl font-bold text-white mb-2" id="authTitle">Join the Elite Fleet</h2>
                <p class="text-gray-400 mb-6" id="authDesc">Start tracking your earnings like a pro.</p>

                <div id="authError"
                    class="hidden mb-4 p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-start gap-3">
                    <i data-lucide="alert-circle" class="text-rose-500 shrink-0 w-5 h-5"></i>
                    <p class="text-sm text-rose-400 font-medium" id="authErrorText"></p>
                </div>

                <form id="authForm" class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-gray-400">Email or Mobile</label>
                        <input type="text" id="identifier"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-amber-500 outline-none"
                            required>
                    </div>
                    <div id="cabNumberGroup">
                        <label class="text-xs font-medium text-gray-400">Cab Number (Optional)</label>
                        <input type="text" id="cab_number"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-amber-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400">Password</label>
                        <input type="password" id="password"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-amber-500 outline-none"
                            required minlength="6">
                    </div>
                    <button type="submit"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold py-4 rounded-full mt-4"
                        id="authSubmitBtn">Create Free Account</button>
                </form>

                <div class="mt-8 text-center">
                    <button type="button" id="toggleAuthMode"
                        class="text-amber-500 font-semibold hover:text-amber-400">Already have an account? Sign
                        In</button>
                </div>
            </div>

            <div class="relative h-16 w-full overflow-hidden border-y border-gray-800 bg-gray-900/50 mt-auto mb-10">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-full border-t-2 border-dashed border-gray-700"></div>
                </div>
                <div class="absolute bottom-4 animate-drive"><i data-lucide="car" class="text-amber-500 w-8 h-8"></i>
                </div>
            </div>
        </div>

        <!-- MAIN APP WRAPPER -->
        <div id="mainAppView" class="spa-section flex flex-col h-full hidden">
            <!-- Header -->
            <header class="pt-10 pb-4 px-6 bg-gradient-to-b from-black to-gray-900">
                <div class="flex justify-between items-center">
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-amber-200 to-amber-500 bg-clip-text text-transparent">
                            CabManager</h1>
                        <p class="text-xs text-gray-400 font-medium tracking-wider mt-1" id="headerCabNumber">Loading...
                        </p>
                    </div>
                    <div
                        class="h-10 w-10 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center">
                        <i data-lucide="car" class="text-amber-400 w-5 h-5"></i>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto pb-24 px-4 custom-scrollbar relative" id="mainContentArea">

                <!-- Dashboard Tab -->
                <div id="tab-dashboard" class="tab-content active-section space-y-6 pt-2">
                    <div
                        class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-5 border border-gray-700 shadow-lg">
                        <h2 class="text-gray-400 text-sm font-medium mb-1">Total Net Income</h2>
                        <div class="flex items-baseline space-x-2 mb-6">
                            <span class="text-3xl font-bold text-white" id="dashNetIncome">₹0</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-950/50 p-3 rounded-xl border border-gray-800">
                                <p class="text-gray-500 text-xs mb-1">Total KM</p>
                                <p class="text-lg font-semibold text-gray-200" id="dashTotalKm">0 km</p>
                            </div>
                            <div class="bg-gray-950/50 p-3 rounded-xl border border-gray-800">
                                <p class="text-gray-500 text-xs mb-1">Total Rides</p>
                                <p class="text-lg font-semibold text-gray-200" id="dashTotalRides">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <h3 class="text-sm font-semibold text-gray-400 mb-3 px-1 uppercase">Recent Activity</h3>
                        <div id="recentActivityList" class="space-y-3"></div>
                    </div>
                </div>

                <!-- Add Ride Tab -->
                <div id="tab-ride" class="tab-content hidden pt-4">
                    <h2 class="text-xl font-semibold mb-6 text-gray-100">Add Income (Ride)</h2>
                    <form id="formAddRide" class="space-y-4">
                        <input type="date" id="r_date"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white" required>
                        <input type="number" id="r_fare" placeholder="Total Fare (₹)"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white" required>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" id="r_km" placeholder="Ride KM"
                                class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white"
                                required>
                            <input type="number" id="r_deadhead" placeholder="Deadhead KM" value="3"
                                class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white"
                                required>
                        </div>
                        <input type="text" id="r_origin" placeholder="Origin"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white">
                        <input type="text" id="r_dest" placeholder="Destination"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white">
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" id="r_mcd" placeholder="MCD Toll (₹)" value="0"
                                class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white">
                            <input type="number" id="r_paid" placeholder="Paid Toll (₹)" value="0"
                                class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white">
                        </div>
                        <button type="submit"
                            class="w-full bg-amber-500 text-gray-950 font-bold py-3.5 rounded-xl mt-6">Save
                            Ride</button>
                    </form>
                </div>

                <!-- Add Expense Tab -->
                <div id="tab-expense" class="tab-content hidden pt-4">
                    <h2 class="text-xl font-semibold mb-6 text-gray-100">Add Expense</h2>
                    <form id="formAddExpense" class="space-y-4">
                        <input type="date" id="e_date"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white" required>
                        <input type="number" id="e_amount" placeholder="Amount (₹)"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white" required>
                        <select id="e_type"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white">
                            <option value="Fuel">Fuel</option>
                            <option value="MCD Toll">MCD Toll</option>
                            <option value="Paid Toll">Paid Toll</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" id="e_desc" placeholder="Description (Optional)"
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white">
                        <button type="submit"
                            class="w-full bg-rose-500 text-white font-bold py-3.5 rounded-xl mt-6">Save
                            Expense</button>
                    </form>
                </div>

                <!-- Settings Tab -->
                <div id="tab-settings" class="tab-content hidden pt-4">
                    <h2 class="text-xl font-semibold mb-6 text-gray-100">Settings</h2>
                    <button id="btnLogout"
                        class="w-full bg-rose-500/10 text-rose-500 font-bold py-3.5 rounded-xl border border-rose-500/20">Sign
                        Out</button>
                </div>
            </main>

            <!-- Bottom Nav -->
            <nav
                class="absolute bottom-0 w-full bg-gray-900/90 backdrop-blur-md border-t border-gray-800 pb-safe z-30 pt-2 pb-4 px-2 flex justify-between items-center">
                <button onclick="switchTab('dashboard')"
                    class="nav-btn flex-1 flex flex-col items-center text-amber-400" data-target="dashboard"><i
                        data-lucide="home" class="w-6 h-6 mb-1"></i><span class="text-[10px]">Home</span></button>
                <button onclick="switchTab('ride')"
                    class="nav-btn flex-1 flex flex-col items-center text-gray-500 hover:text-gray-300"
                    data-target="ride"><i data-lucide="car" class="w-6 h-6 mb-1"></i><span
                        class="text-[10px]">Ride</span></button>
                <button onclick="switchTab('expense')"
                    class="nav-btn flex-1 flex flex-col items-center text-gray-500 hover:text-gray-300"
                    data-target="expense"><i data-lucide="wallet" class="w-6 h-6 mb-1"></i><span
                        class="text-[10px]">Expense</span></button>
                <button onclick="switchTab('settings')"
                    class="nav-btn flex-1 flex flex-col items-center text-gray-500 hover:text-gray-300"
                    data-target="settings"><i data-lucide="settings" class="w-6 h-6 mb-1"></i><span
                        class="text-[10px]">Settings</span></button>
            </nav>
        </div>
    </div>

    <script>
        // Setup jQuery AJAX CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let appState = {
            authMode: 'signup',
            user: null,
            rides: [],
            expenses: []
        };

        // Initialize App
        $(document).ready(function() {
            lucide.createIcons();
            // Set dates to today
            const today = new Date().toISOString().split('T')[0];
            $('#r_date, #e_date').val(today);

            // Check Session
            fetchData();
        });

        // --- AUTH LOGIC ---
        $('#toggleAuthMode').click(() => {
            appState.authMode = appState.authMode === 'signup' ? 'login' : 'signup';
            $('#authTitle').text(appState.authMode === 'signup' ? 'Join the Elite Fleet' : 'Welcome Back');
            $('#authDesc').text(appState.authMode === 'signup' ? 'Start tracking your earnings like a pro.' :
                'Sign in to access your dashboard.');
            $('#authSubmitBtn').text(appState.authMode === 'signup' ? 'Create Free Account' : 'Sign In');
            $('#cabNumberGroup').toggle(appState.authMode === 'signup');
            $('#toggleAuthMode').text(appState.authMode === 'signup' ? 'Already have an account? Sign In' :
                "Don't have an account? Sign Up");
            $('#authError').hide();
        });

        $('#authForm').submit(function(e) {
            e.preventDefault();
            const url = appState.authMode === 'signup' ? '/api/register' : '/api/login';
            const data = {
                identifier: $('#identifier').val(),
                password: $('#password').val(),
                cab_number: $('#cab_number').val()
            };

            $.post(url, data)
                .done(function(res) {
                    appState.user = res.user;
                    fetchData(); // Load dashboard data
                })
                .fail(function(err) {
                    $('#authError').show().removeClass('hidden');
                    $('#authErrorText').text(err.responseJSON.error || err.responseJSON.message ||
                        'Authentication failed.');
                });
        });

        $('#btnLogout').click(function() {
            $.post('/api/logout').done(() => {
                appState.user = null;
                $('#mainAppView').hide();
                $('#landingView').show();
            });
        });

        // --- DATA & UI LOGIC ---
        function fetchData() {
            $('#loadingOverlay').removeClass('hidden').addClass('flex');
            $.get('/api/data')
                .done(function(res) {
                    appState.user = res.user;
                    appState.rides = res.rides;
                    appState.expenses = res.expenses;

                    $('#landingView').hide();
                    $('#mainAppView').removeClass('hidden');
                    $('#headerCabNumber').text(appState.user.cab_number);
                    renderDashboard();
                })
                .fail(function(err) {
                    // Not authenticated
                    $('#landingView').show();
                    $('#mainAppView').addClass('hidden');
                })
                .always(function() {
                    $('#loadingOverlay').removeClass('flex').addClass('hidden');
                });
        }

        function renderDashboard() {
            let totalIncome = appState.rides.reduce((sum, r) => sum + parseFloat(r.fare), 0);
            let totalExpense = appState.expenses.reduce((sum, e) => sum + parseFloat(e.amount), 0);
            let net = totalIncome - totalExpense;
            let km = appState.rides.reduce((sum, r) => sum + parseFloat(r.km) + parseFloat(r.deadhead_km), 0);

            $('#dashNetIncome').text(`₹${net.toFixed(0)}`);
            $('#dashTotalKm').text(`${km} km`);
            $('#dashTotalRides').text(appState.rides.length);

            let activityHtml = '';
            appState.rides.slice(0, 3).forEach(r => {
                activityHtml += `
                <div class="flex items-center justify-between p-3 bg-gray-800/40 rounded-xl border border-gray-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg"><i data-lucide="trending-up" class="w-4 h-4"></i></div>
                        <div><p class="text-sm font-medium text-gray-200">${r.destination || 'Ride'}</p><p class="text-xs text-gray-500">${r.date}</p></div>
                    </div>
                    <p class="text-sm font-bold text-emerald-400">+₹${r.fare}</p>
                </div>`;
            });
            appState.expenses.slice(0, 2).forEach(e => {
                activityHtml += `
                <div class="flex items-center justify-between p-3 bg-gray-800/40 rounded-xl border border-gray-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-rose-500/10 text-rose-400 rounded-lg"><i data-lucide="trending-down" class="w-4 h-4"></i></div>
                        <div><p class="text-sm font-medium text-gray-200">${e.type}</p><p class="text-xs text-gray-500">${e.date}</p></div>
                    </div>
                    <p class="text-sm font-bold text-rose-400">-₹${e.amount}</p>
                </div>`;
            });
            $('#recentActivityList').html(activityHtml || '<p class="text-xs text-gray-500">No recent activity.</p>');
            lucide.createIcons();
        }

        // --- FORM SUBMISSIONS ---
        $('#formAddRide').submit(function(e) {
            e.preventDefault();
            const btn = $(this).find('button');
            btn.text('Saving...').prop('disabled', true);
            $.post('/api/rides', {
                date: $('#r_date').val(),
                fare: $('#r_fare').val(),
                km: $('#r_km').val(),
                deadhead_km: $('#r_deadhead').val(),
                origin: $('#r_origin').val(),
                destination: $('#r_dest').val(),
                mcd_toll: $('#r_mcd').val(),
                paid_toll: $('#r_paid').val()
            }).done(() => {
                btn.text('Save Ride').prop('disabled', false);
                this.reset();
                fetchData();
                switchTab('dashboard');
            });
        });

        $('#formAddExpense').submit(function(e) {
            e.preventDefault();
            const btn = $(this).find('button');
            btn.text('Saving...').prop('disabled', true);
            $.post('/api/expenses', {
                date: $('#e_date').val(),
                amount: $('#e_amount').val(),
                type: $('#e_type').val(),
                description: $('#e_desc').val()
            }).done(() => {
                btn.text('Save Expense').prop('disabled', false);
                this.reset();
                fetchData();
                switchTab('dashboard');
            });
        });

        // --- NAVIGATION ---
        function switchTab(tabId) {
            $('.tab-content').addClass('hidden').removeClass('active-section');
            $(`#tab-${tabId}`).removeClass('hidden').addClass('active-section');

            $('.nav-btn').removeClass('text-amber-400 scale-110').addClass('text-gray-500');
            $(`.nav-btn[data-target="${tabId}"]`).removeClass('text-gray-500').addClass('text-amber-400 scale-110');
        }
    </script>
</body>

</html>
