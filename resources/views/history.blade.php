@extends('layouts.app')

@section('content')
    <div class="pt-2">
        <div class="flex items-center gap-2 mb-6">
            <div class="h-6 w-1 bg-amber-500 rounded-full"></div>
            <h2 class="text-xl font-semibold text-gray-100">Transaction History</h2>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-800/40 p-4 rounded-2xl border border-gray-700/50 flex-col gap-3 shadow-sm mb-6 flex">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-300 font-semibold uppercase tracking-wider">
                    <i data-lucide="filter" class="w-4 h-4 text-amber-500"></i> Date Filter
                </div>
                <button id="clearHistoryFilter"
                    class="hidden text-xs font-bold bg-rose-500/10 text-rose-400 px-3 py-1 rounded-full hover:bg-rose-500/20 transition-colors">Clear</button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div
                    class="bg-gray-900 rounded-xl px-4 py-2 border border-gray-700 focus-within:border-amber-500 transition-colors">
                    <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold block mb-1">From Date</span>
                    <input type="date" id="hFilterFrom"
                        class="bg-transparent border-none text-gray-200 outline-none w-full text-sm [color-scheme:dark]">
                </div>
                <div
                    class="bg-gray-900 rounded-xl px-4 py-2 border border-gray-700 focus-within:border-amber-500 transition-colors">
                    <span class="text-[10px] uppercase tracking-wider text-gray-500 font-bold block mb-1">To Date</span>
                    <input type="date" id="hFilterTo"
                        class="bg-transparent border-none text-gray-200 outline-none w-full text-sm [color-scheme:dark]">
                </div>
            </div>
            <button id="applyHistoryFilter"
                class="w-full mt-2 bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold py-3 rounded-xl transition-colors active:scale-95 shadow-lg">Apply
                Filter</button>
        </div>

        <!-- History Content -->
        <div class="space-y-4">
            <div id="historyList" class="space-y-3">
                <p class="text-center text-sm text-gray-500 py-4">Loading history...</p>
            </div>

            <!-- Pagination Controls -->
            <div id="paginationControls"
                class="hidden flex justify-between items-center pt-4 border-t border-gray-800 pb-4">
                <button id="prevPage"
                    class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-medium">Previous</button>
                <span id="pageInfo" class="text-sm text-gray-500 font-medium tracking-wide">Page 1 of 1</span>
                <button id="nextPage"
                    class="px-4 py-2 bg-gray-800 text-gray-300 rounded-xl hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-medium">Next</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentPage = 1;

        function loadHistory(page = 1) {
            const fromDate = $('#hFilterFrom').val();
            const toDate = $('#hFilterTo').val();

            if (fromDate || toDate) {
                $('#clearHistoryFilter').removeClass('hidden');
            } else {
                $('#clearHistoryFilter').addClass('hidden');
            }

            $('#historyList').html(
                '<div class="flex justify-center py-6"><i data-lucide="loader-2" class="animate-spin text-gray-500 w-6 h-6"></i></div>'
                );
            lucide.createIcons();

            $.get("{{ route('api.history.data') }}", {
                page: page,
                from_date: fromDate,
                to_date: toDate
            }, function(res) {
                currentPage = res.current_page;
                renderHistory(res.data);
                renderPagination(res);
            }).fail(function() {
                $('#historyList').html(
                    '<p class="text-center text-sm text-rose-400 py-4">Failed to load history.</p>');
            });
        }

        function renderHistory(items) {
            let html = '';
            if (items.length === 0) {
                html =
                    '<div class="text-center py-8 bg-gray-800/20 rounded-2xl border border-gray-700/30"><p class="text-gray-400 text-sm">No records found for this period.</p></div>';
            } else {
                items.forEach(item => {
                    if (item.record_type === 'ride') {
                        let routeDisplay = 'Ride';
                        if (item.origin && item.destination) {
                            routeDisplay =
                                `${item.origin} <span class="text-gray-500 mx-1">→</span> ${item.destination}`;
                        } else if (item.destination) {
                            routeDisplay = item.destination;
                        } else if (item.origin) {
                            routeDisplay = item.origin;
                        }
                        html += `
                    <div class="flex items-center justify-between p-4 bg-gray-800/40 rounded-2xl border border-gray-700/50 hover:bg-gray-800 transition-all shadow-sm">
                        <div class="flex items-center space-x-4 w-[75%]">
                            <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl shrink-0"><i data-lucide="trending-up" class="w-5 h-5"></i></div>
                            <div class="min-w-0">
                                <p class="text-base font-bold text-gray-200 truncate">${routeDisplay}</p>
                                <p class="text-xs text-gray-500 font-medium">${item.date} • Ride Fare</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-emerald-400 shrink-0">+₹${item.fare}</p>
                    </div>`;
                    } else {
                        html += `
                    <div class="flex items-center justify-between p-4 bg-gray-800/40 rounded-2xl border border-gray-700/50 hover:bg-gray-800 transition-all shadow-sm">
                        <div class="flex items-center space-x-4 w-[75%]">
                            <div class="p-3 bg-rose-500/10 text-rose-400 rounded-xl shrink-0"><i data-lucide="trending-down" class="w-5 h-5"></i></div>
                            <div class="min-w-0">
                                <p class="text-base font-bold text-gray-200 truncate">${item.type}</p>
                                <p class="text-xs text-gray-500 font-medium">${item.date} • Expense</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-rose-400 shrink-0">-₹${item.amount}</p>
                    </div>`;
                    }
                });
            }
            $('#historyList').html(html);
            lucide.createIcons();
        }

        function renderPagination(res) {
            if (res.last_page <= 1) {
                $('#paginationControls').addClass('hidden').removeClass('flex');
                return;
            }
            $('#paginationControls').removeClass('hidden').addClass('flex');

            $('#pageInfo').text(`Page ${res.current_page} of ${res.last_page}`);

            $('#prevPage').prop('disabled', res.current_page <= 1);
            $('#nextPage').prop('disabled', res.current_page >= res.last_page);
        }

        $(document).ready(function() {
            loadHistory();

            $('#applyHistoryFilter').click(function() {
                loadHistory(1);
            });

            $('#clearHistoryFilter').click(function() {
                $('#hFilterFrom, #hFilterTo').val('');
                loadHistory(1);
            });

            $('#prevPage').click(function() {
                if (currentPage > 1) loadHistory(currentPage - 1);
            });

            $('#nextPage').click(function() {
                loadHistory(currentPage + 1);
            });
        });
    </script>
@endpush
