@extends('layouts.app')

@section('content')
    <div class="space-y-6 pb-6">

        <!-- Filter Section -->
        <div id="filterSection"
            class="hidden bg-gray-800/40 p-4 md:p-5 rounded-2xl border border-gray-700/50 flex-col gap-3 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-300 font-semibold uppercase tracking-wider">
                    <i data-lucide="calendar" class="w-4 h-4 text-amber-500"></i> Analytics Filter
                </div>
                <button id="clearFilter"
                    class="hidden text-xs font-bold bg-rose-500/10 text-rose-400 px-3 py-1 rounded-full hover:bg-rose-500/20 transition-colors">Clear
                    Filter</button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div
                    class="bg-gray-900 rounded-xl px-4 py-2 border border-gray-700 focus-within:border-amber-500 transition-colors">
                    <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold block mb-1">Date From</span>
                    <input type="date" id="filterFrom"
                        class="bg-transparent border-none text-gray-200 outline-none w-full text-sm [color-scheme:dark]">
                </div>
                <div
                    class="bg-gray-900 rounded-xl px-4 py-2 border border-gray-700 focus-within:border-amber-500 transition-colors">
                    <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold block mb-1">Date To</span>
                    <input type="date" id="filterTo"
                        class="bg-transparent border-none text-gray-200 outline-none w-full text-sm [color-scheme:dark]">
                </div>
            </div>
        </div>

        <!-- Empty State (No Data) -->
        <div id="emptyState"
            class="hidden text-center py-16 px-4 bg-gray-800/20 rounded-3xl border border-gray-700/30 border-dashed mt-4">
            <div class="bg-gray-800/50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i data-lucide="car" class="text-gray-500 w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-200 mb-2">No entries yet</h3>
            <p class="text-gray-500 text-sm mb-8 max-w-[300px] mx-auto leading-relaxed">Start by adding your first ride or
                expense to see your earnings dashboard come alive.</p>
            <a href="{{ route('ride.view') }}"
                class="bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold py-3.5 px-8 rounded-full inline-flex items-center justify-center gap-2 transition-all shadow-[0_0_30px_rgba(245,158,11,0.3)] hover:scale-105">
                <i data-lucide="plus-circle" class="w-5 h-5"></i> Add First Ride
            </a>
        </div>

        <!-- Dashboard Content -->
        <div id="dashboardContent" class="hidden space-y-6">

            <!-- Main Stats Card -->
            <div
                class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-6 md:p-8 border border-gray-700 shadow-xl relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full blur-[60px] -mr-16 -mt-16 group-hover:bg-amber-500/20 transition-all duration-700">
                </div>

                <h2 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2 relative z-10"
                    id="netIncomeLabel">Total Net Income</h2>
                <div class="flex items-end space-x-3 mb-8 relative z-10">
                    <span class="text-4xl md:text-5xl font-black text-white tracking-tight" id="statNetIncome">₹0</span>
                    <span id="statNetBadge"
                        class="text-sm font-bold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 mb-1">+
                        Profit</span>
                </div>

                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <div class="bg-black/20 p-4 rounded-2xl border border-gray-700/50 backdrop-blur-sm">
                        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Filtered KM</p>
                        <p class="text-2xl font-bold text-gray-200" id="statFilteredKm">0 <span
                                class="text-sm font-medium text-gray-500">km</span></p>
                    </div>
                    <div class="bg-black/20 p-4 rounded-2xl border border-gray-700/50 backdrop-blur-sm">
                        <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Filtered Rides</p>
                        <p class="text-2xl font-bold text-gray-200" id="statFilteredRides">0</p>
                    </div>
                </div>
            </div>

            <!-- Grid Stats (Responsive: 2 cols mobile, 4 cols desktop) -->
            <div id="gridStatsContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    class="bg-gray-800/40 p-5 rounded-3xl border border-gray-700/50 h-32 flex flex-col justify-between hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-400 text-xs font-semibold uppercase">Today's Net</p>
                        <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400"><i data-lucide="trending-up"
                                class="w-4 h-4"></i></div>
                    </div>
                    <p class="text-2xl font-bold text-gray-100" id="statTodayNet">₹0</p>
                </div>
                <div
                    class="bg-gray-800/40 p-5 rounded-3xl border border-gray-700/50 h-32 flex flex-col justify-between hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-400 text-xs font-semibold uppercase">Today's KM</p>
                        <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400"><i data-lucide="car" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-100" id="statTodayKm">0 km</p>
                </div>
                <div
                    class="bg-gray-800/40 p-5 rounded-3xl border border-gray-700/50 h-32 flex flex-col justify-between hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-400 text-xs font-semibold uppercase">Monthly Net</p>
                        <div class="p-2 rounded-xl bg-amber-500/10 text-amber-400"><i data-lucide="wallet"
                                class="w-4 h-4"></i></div>
                    </div>
                    <p class="text-2xl font-bold text-gray-100" id="statMonthlyNet">₹0</p>
                </div>
                <div
                    class="bg-gray-800/40 p-5 rounded-3xl border border-gray-700/50 h-32 flex flex-col justify-between hover:bg-gray-800 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-400 text-xs font-semibold uppercase">Monthly KM</p>
                        <div class="p-2 rounded-xl bg-purple-500/10 text-purple-400"><i data-lucide="map-pin"
                                class="w-4 h-4"></i></div>
                    </div>
                    <p class="text-2xl font-bold text-gray-100" id="statMonthlyKm">0 km</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="pt-6">
                <h3 class="text-sm font-bold text-gray-400 mb-4 px-1 uppercase tracking-wider" id="activityLabel">Recent
                    Activity</h3>
                <div id="activityList" class="space-y-3">
                    <p class="text-center text-sm text-gray-500 py-4">Loading data...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Details & Actions Modal -->
    <div id="itemModal"
        class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm items-center justify-center p-4 animate-in fade-in duration-200">
        <div
            class="bg-gray-900 border border-gray-700 rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl flex flex-col animate-in zoom-in-95 duration-200">
            <!-- Header -->
            <div class="flex justify-between items-center p-5 border-b border-gray-800 bg-gray-800/30">
                <h3 class="font-bold text-gray-100 flex items-center gap-2 text-lg" id="modalTitle">Details</h3>
                <button onclick="closeModal()"
                    class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-xl transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <!-- Content -->
            <div class="p-6 space-y-4" id="modalContent"></div>
            <!-- Actions -->
            <div class="flex p-5 gap-3 bg-gray-800/30 border-t border-gray-800">
                <button id="modalDeleteBtn"
                    class="flex-1 flex justify-center items-center gap-2 py-3 rounded-xl bg-rose-500/10 text-rose-500 font-bold hover:bg-rose-500/20 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                </button>
                <button id="modalEditBtn"
                    class="flex-1 flex justify-center items-center gap-2 py-3 rounded-xl bg-amber-500/10 text-amber-500 font-bold hover:bg-amber-500/20 transition-colors">
                    <i data-lucide="edit-2" class="w-4 h-4"></i> Edit
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentData = {
            rides: [],
            expenses: []
        };
        let currentItem = null;

        function loadDashboardData() {
            const fromDate = $('#filterFrom').val();
            const toDate = $('#filterTo').val();
            const isFiltered = fromDate || toDate;

            $.get("{{ route('api.dashboard.data') }}", {
                from_date: fromDate,
                to_date: toDate
            }, function(res) {

                // Determine if there is any data (checks stats directly returned from DB)
                const hasData = res.stats.filteredRidesCount > 0 || res.recent_expenses.length > 0 || Number(res
                    .stats.monthlyNet) !== 0 || Number(res.stats.todayNet) !== 0;

                if (!hasData && !isFiltered) {
                    $('#emptyState').removeClass('hidden');
                    $('#dashboardContent, #filterSection').addClass('hidden');
                    return;
                } else {
                    $('#emptyState').addClass('hidden');
                    $('#dashboardContent, #filterSection').removeClass('hidden');
                    $('#filterSection').addClass('flex');
                }

                if (isFiltered) {
                    $('#clearFilter').removeClass('hidden');
                    $('#gridStatsContainer').hide();
                    $('#netIncomeLabel').text('Net Income (Filtered)');
                    $('#activityLabel').text('Filtered Activity');
                } else {
                    $('#clearFilter').addClass('hidden');
                    $('#gridStatsContainer').show();
                    $('#netIncomeLabel').text('Total Net Income');
                    $('#activityLabel').text('Recent Activity');
                }

                currentData.rides = res.recent_rides || [];
                currentData.expenses = res.recent_expenses || [];

                const stats = res.stats;
                $('#statNetIncome').text(`₹${Number(stats.filteredNet).toLocaleString()}`);

                if (stats.filteredNet >= 0) {
                    $('#statNetBadge').removeClass('bg-rose-500/20 text-rose-400').addClass(
                        'bg-emerald-500/20 text-emerald-400').text('+ Profit');
                } else {
                    $('#statNetBadge').removeClass('bg-emerald-500/20 text-emerald-400').addClass(
                        'bg-rose-500/20 text-rose-400').text('- Loss');
                }

                $('#statFilteredKm').text(`${stats.filteredKm}`);
                $('#statFilteredRides').text(stats.filteredRidesCount);

                $('#statTodayNet').text(`₹${Number(stats.todayNet).toLocaleString()}`);
                $('#statTodayKm').text(`${stats.todayKm} km`);
                $('#statMonthlyNet').text(`₹${Number(stats.monthlyNet).toLocaleString()}`);
                $('#statMonthlyKm').text(`${stats.monthlyKm} km`);

                // Render Activity List
                let html = '';

                if (currentData.rides.length === 0 && currentData.expenses.length === 0) {
                    html =
                        '<div class="text-center py-8 bg-gray-800/20 rounded-2xl border border-gray-700/30"><p class="text-gray-400 text-sm">No activity found for this period.</p></div>';
                } else {
                    currentData.rides.forEach(r => {
                        let routeDisplay = 'Ride';
                        if (r.origin && r.destination) {
                            routeDisplay =
                                `${r.origin} <span class="text-gray-500 mx-1">→</span> ${r.destination}`;
                        } else if (r.destination) {
                            routeDisplay = r.destination;
                        } else if (r.origin) {
                            routeDisplay = r.origin;
                        }

                        html += `
                    <div onclick="openModal('ride', ${r.id})" class="cursor-pointer flex items-center justify-between p-4 bg-gray-800/40 rounded-2xl border border-gray-700/50 hover:bg-gray-800 transition-all active:scale-[0.98] shadow-sm hover:shadow-md group">
                        <div class="flex items-center space-x-4 w-[75%]">
                            <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl group-hover:bg-emerald-500/20 transition-colors shrink-0"><i data-lucide="trending-up" class="w-5 h-5"></i></div>
                            <div class="min-w-0">
                                <p class="text-base font-bold text-gray-200 truncate" title="${r.origin} -> ${r.destination}">${routeDisplay}</p>
                                <p class="text-xs text-gray-500 font-medium">${r.date} • ${Number(r.km) + Number(r.deadhead_km)}km</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-emerald-400 shrink-0">+₹${r.fare}</p>
                    </div>`;
                    });
                    currentData.expenses.forEach(e => {
                        html += `
                    <div onclick="openModal('expense', ${e.id})" class="cursor-pointer flex items-center justify-between p-4 bg-gray-800/40 rounded-2xl border border-gray-700/50 hover:bg-gray-800 transition-all active:scale-[0.98] shadow-sm hover:shadow-md group">
                        <div class="flex items-center space-x-4 w-[75%]">
                            <div class="p-3 bg-rose-500/10 text-rose-400 rounded-xl group-hover:bg-rose-500/20 transition-colors shrink-0"><i data-lucide="trending-down" class="w-5 h-5"></i></div>
                            <div class="min-w-0">
                                <p class="text-base font-bold text-gray-200 truncate">${e.type}</p>
                                <p class="text-xs text-gray-500 font-medium">${e.date}</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-rose-400 shrink-0">-₹${e.amount}</p>
                    </div>`;
                    });
                }

                $('#activityList').html(html);
                lucide.createIcons();
            });
        }

        // Modal Interaction Logic
        window.openModal = function(type, id) {
            currentItem = {
                type,
                id
            };
            let data = type === 'ride' ? currentData.rides.find(r => r.id === id) : currentData.expenses.find(e => e
                .id === id);

            if (type === 'ride') {
                $('#modalTitle').html('<i data-lucide="car" class="w-5 h-5 text-amber-500"></i> Ride Details');
                $('#modalContent').html(`
                <div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-500">Date</span><span class="text-sm font-bold text-gray-200">${data.date}</span></div>
                <div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-500">Route</span><span class="text-sm font-bold text-gray-200 text-right ml-4 break-words">${data.origin || 'N/A'} → ${data.destination || 'N/A'}</span></div>
                <div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-500">Total KM</span><span class="text-sm font-bold text-gray-200">${Number(data.km) + Number(data.deadhead_km)} km</span></div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-800/50"><span class="text-sm font-bold text-gray-400">Total Fare</span><span class="text-lg font-black text-emerald-400">₹${data.fare}</span></div>
                <div class="flex justify-between items-center"><span class="text-xs font-medium text-gray-500">Tolls Collected</span><span class="text-xs font-bold text-gray-400">₹${Number(data.mcd_toll) + Number(data.paid_toll)}</span></div>
            `);
            } else {
                $('#modalTitle').html('<i data-lucide="wallet" class="w-5 h-5 text-rose-500"></i> Expense Details');
                $('#modalContent').html(`
                <div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-500">Date</span><span class="text-sm font-bold text-gray-200">${data.date}</span></div>
                <div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-500">Type</span><span class="text-sm font-bold text-gray-200">${data.type}</span></div>
                ${data.description ? `<div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-500">Desc</span><span class="text-sm font-bold text-gray-200 text-right ml-4 break-words">${data.description}</span></div>` : ''}
                <div class="flex justify-between items-center pt-2 border-t border-gray-800/50"><span class="text-sm font-bold text-gray-400">Total Amount</span><span class="text-lg font-black text-rose-400">₹${data.amount}</span></div>
            `);
            }

            lucide.createIcons();
            $('#itemModal').removeClass('hidden').addClass('flex');
        };

        window.closeModal = function() {
            $('#itemModal').addClass('hidden').removeClass('flex');
            currentItem = null;
        };

        // Handle Delete
        $('#modalDeleteBtn').click(function() {
            if (!currentItem) return;

            const endpoint = currentItem.type === 'ride' ?
                `{{ url('api/rides') }}/${currentItem.id}` :
                `{{ url('api/expenses') }}/${currentItem.id}`;

            if (confirm('Are you sure you want to permanently delete this record?')) {
                $.post(endpoint, {
                        _method: 'DELETE'
                    })
                    .done(function(res) {
                        showAlert(res.message, 'success');
                        closeModal();
                        loadDashboardData();
                    })
                    .fail(function() {
                        showAlert('Failed to delete.', 'error');
                    });
            }
        });

        // Handle Edit Route
        $('#modalEditBtn').click(function() {
            if (!currentItem) return;
            const route = currentItem.type === 'ride' ? "{{ route('ride.view') }}" : "{{ route('expense.view') }}";
            window.location.href = `${route}?edit=${currentItem.id}`;
        });

        $(document).ready(function() {
            loadDashboardData();

            $('#filterFrom, #filterTo').on('change', loadDashboardData);
            $('#clearFilter').on('click', function() {
                $('#filterFrom, #filterTo').val('');
                loadDashboardData();
            });
        });
    </script>
@endpush
