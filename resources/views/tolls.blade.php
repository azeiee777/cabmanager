@extends('layouts.app')

@section('content')
    <div class="pt-2">
        <!-- Filter Section -->
        <div class="bg-gray-800/40 p-2.5 rounded-xl border border-gray-700/50 flex flex-col gap-1.5 mb-6">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-1.5 text-xs text-gray-400 font-medium">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-500/80"></i> Toll Date Filter
                </div>
                <button id="clearTollFilter"
                    class="hidden text-[10px] font-medium bg-rose-500/10 text-rose-400 px-2.5 py-0.5 rounded-full hover:bg-rose-500/20 transition-colors">Clear</button>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex-1 bg-gray-900 rounded-lg px-2.5 py-1 border border-gray-700/80">
                    <span class="text-[9px] uppercase tracking-wider text-gray-500 font-bold block mb-0.5">From</span>
                    <input type="date" id="tFilterFrom"
                        class="bg-transparent border-none text-gray-200 outline-none w-full text-xs h-5 [color-scheme:dark]">
                </div>
                <div class="flex-1 bg-gray-900 rounded-lg px-2.5 py-1 border border-gray-700/80">
                    <span class="text-[9px] uppercase tracking-wider text-gray-500 font-bold block mb-0.5">To</span>
                    <input type="date" id="tFilterTo"
                        class="bg-transparent border-none text-gray-200 outline-none w-full text-xs h-5 [color-scheme:dark]">
                </div>
            </div>
        </div>

        <!-- Main Stats Card -->
        <div
            class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden mb-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
            <h2 class="text-gray-400 text-sm font-medium mb-1 relative z-10" id="tollNetLabel">Total Net Toll Profit</h2>
            <div class="flex items-baseline space-x-2 relative z-10">
                <span class="text-3xl font-bold text-white" id="statTollNet">₹0</span>
                <span id="statTollBadge"
                    class="text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400">+ Surplus</span>
            </div>
        </div>

        <!-- Detailed Analysis -->
        <div>
            <h3 class="text-sm font-semibold text-gray-400 mb-3 px-1 uppercase tracking-wider">Breakdown</h3>
            <div class="space-y-3">
                <div class="bg-gray-800/40 p-4 rounded-xl border border-gray-700/50 flex flex-col gap-3">
                    <div class="flex justify-between items-center border-b border-gray-700/50 pb-2">
                        <span class="font-semibold text-gray-200 flex items-center gap-2"><i data-lucide="receipt"
                                class="w-4 h-4 text-amber-500"></i> MCD Toll</span>
                        <span id="mcdNet" class="font-bold">Net: ₹0</span>
                    </div>
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-400">Collected: <span id="mcdCollected"
                                class="text-emerald-400 ml-1">₹0</span></span>
                        <span class="text-gray-400">Paid: <span id="mcdSpent" class="text-rose-400 ml-1">₹0</span></span>
                    </div>
                </div>

                <div class="bg-gray-800/40 p-4 rounded-xl border border-gray-700/50 flex flex-col gap-3">
                    <div class="flex justify-between items-center border-b border-gray-700/50 pb-2">
                        <span class="font-semibold text-gray-200 flex items-center gap-2"><i data-lucide="receipt"
                                class="w-4 h-4 text-blue-400"></i> Paid Toll</span>
                        <span id="paidNet" class="font-bold">Net: ₹0</span>
                    </div>
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-400">Collected: <span id="paidCollected"
                                class="text-emerald-400 ml-1">₹0</span></span>
                        <span class="text-gray-400">Paid: <span id="paidSpent" class="text-rose-400 ml-1">₹0</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadTollData() {
            const fromDate = $('#tFilterFrom').val();
            const toDate = $('#tFilterTo').val();

            if (fromDate || toDate) {
                $('#clearTollFilter').removeClass('hidden');
                $('#tollNetLabel').text('Net Toll Profit (Filtered)');
            } else {
                $('#clearTollFilter').addClass('hidden');
                $('#tollNetLabel').text('Total Net Toll Profit');
            }

            $.get("{{ route('api.tolls.data') }}", {
                from_date: fromDate,
                to_date: toDate
            }, function(res) {
                const stats = res.tollStats;

                $('#statTollNet').text(`₹${Number(stats.totalNet).toLocaleString()}`);
                if (stats.totalNet >= 0) {
                    $('#statTollBadge').removeClass('bg-rose-500/20 text-rose-400').addClass(
                        'bg-emerald-500/20 text-emerald-400').text('+ Surplus');
                } else {
                    $('#statTollBadge').removeClass('bg-emerald-500/20 text-emerald-400').addClass(
                        'bg-rose-500/20 text-rose-400').text('- Deficit');
                }

                $('#mcdCollected').text(`₹${stats.collectedMcd}`);
                $('#mcdSpent').text(`₹${stats.spentMcd}`);
                $('#mcdNet').text(`Net: ₹${stats.netMcd}`).attr('class',
                    `font-bold ${stats.netMcd >= 0 ? 'text-emerald-400' : 'text-rose-400'}`);

                $('#paidCollected').text(`₹${stats.collectedPaid}`);
                $('#paidSpent').text(`₹${stats.spentPaid}`);
                $('#paidNet').text(`Net: ₹${stats.netPaid}`).attr('class',
                    `font-bold ${stats.netPaid >= 0 ? 'text-emerald-400' : 'text-rose-400'}`);
            });
        }

        $(document).ready(function() {
            loadTollData();
            $('#tFilterFrom, #tFilterTo').on('change', loadTollData);
            $('#clearTollFilter').on('click', function() {
                $('#tFilterFrom, #tFilterTo').val('');
                loadTollData();
            });
        });
    </script>
@endpush
