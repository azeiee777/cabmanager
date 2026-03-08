@extends('layouts.app')

@section('content')
    <div class="pt-2 animate-in fade-in slide-in-from-bottom-4 duration-300">
        <div class="flex items-center gap-2 mb-6">
            <div class="h-6 w-1 bg-yellow-500 rounded-full"></div>
            <h2 class="text-xl font-semibold text-gray-100" id="pageTitle">Add Income (Ride)</h2>
        </div>

        <form id="apiRideForm" class="space-y-5">
            <!-- Date and Fare: Stacked on mobile -->
            <div class="grid grid-cols-1 gap-5">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500 ml-1 uppercase tracking-wider">Date</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-lg pointer-events-none">📅</span>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-900 border border-gray-800 text-gray-200 rounded-xl h-14 pl-12 pr-4 focus:ring-2 focus:ring-yellow-500 focus:border-transparent appearance-none leading-none"
                            style="display: flex; align-items: center;" required>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500 ml-1 uppercase tracking-wider">Total Fare (₹)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₹</span>
                        <input type="number" name="fare" placeholder="e.g. 500"
                            class="w-full bg-gray-900 border border-gray-800 text-gray-200 rounded-xl h-14 pl-10 pr-4 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                            required>
                    </div>
                </div>
            </div>

            <!-- KM Row: Grid for compact info -->
            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-800/50">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-600 uppercase tracking-widest ml-1">Ride KM</label>
                    <input type="number" name="km" placeholder="Distance"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl h-14 px-4 text-white focus:ring-2 focus:ring-yellow-500"
                        required>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-600 uppercase tracking-widest ml-1">Deadhead KM</label>
                    <input type="number" name="deadhead_km" value="3"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl h-14 px-4 text-white focus:ring-2 focus:ring-yellow-500"
                        required>
                </div>
            </div>

            <!-- Locations -->
            <div class="space-y-4 pt-2 border-t border-gray-800/50">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500 ml-1">Origin (Pickup)</label>
                    <input type="text" name="origin" placeholder="Pickup location"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl h-14 px-4 text-white focus:ring-2 focus:ring-yellow-500">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500 ml-1">Destination (Drop)</label>
                    <input type="text" name="destination" placeholder="Drop location"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl h-14 px-4 text-white focus:ring-2 focus:ring-yellow-500">
                </div>
            </div>

            <!-- Tolls: Stacked on mobile to prevent label overlap -->
            <div class="grid grid-cols-1 gap-4 bg-gray-950/50 p-4 rounded-2xl border border-gray-900 mt-2">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">MCD Toll Collected
                        (₹)</label>
                    <input type="number" name="mcd_toll" value="0"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl h-12 px-4 text-white focus:ring-2 focus:ring-yellow-500">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Paid Toll Collected
                        (₹)</label>
                    <input type="number" name="paid_toll" value="0"
                        class="w-full bg-gray-900 border border-gray-800 rounded-xl h-12 px-4 text-white focus:ring-2 focus:ring-yellow-500">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" id="saveRideBtn"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-black py-5 rounded-2xl shadow-xl transition-all active:scale-95 text-lg">
                    SAVE RIDE INFO
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');

            if (editId) {
                $('#pageTitle').text('Edit Ride Details');
                $('#saveRideBtn').text('UPDATE RIDE INFO');

                $.get(`{{ url('api/rides') }}/${editId}`, function(res) {
                    const r = res.ride;
                    $('input[name="date"]').val(r.date);
                    $('input[name="fare"]').val(r.fare);
                    $('input[name="km"]').val(r.km);
                    $('input[name="deadhead_km"]').val(r.deadhead_km);
                    $('input[name="origin"]').val(r.origin);
                    $('input[name="destination"]').val(r.destination);
                    $('input[name="mcd_toll"]').val(r.mcd_toll);
                    $('input[name="paid_toll"]').val(r.paid_toll);
                    $('#apiRideForm').data('edit-id', editId);
                }).fail(function() {
                    showAlert('Failed to load ride details.', 'error');
                });
            }

            $('#apiRideForm').submit(function(e) {
                e.preventDefault();
                const id = $(this).data('edit-id');
                const url = id ? `{{ url('api/rides') }}/${id}` : "{{ route('api.rides.store') }}";
                const btn = $('#saveRideBtn');
                btn.text('PROCESSING...').prop('disabled', true);

                let formData = $(this).serialize();
                if (id) formData += '&_method=PUT';

                $.post(url, formData)
                    .done(function(res) {
                        showAlert(res.message, 'success');
                        setTimeout(() => window.location.href = "{{ route('dashboard.view') }}", 100);
                    })
                    .fail(function(err) {
                        showAlert(err.responseJSON.message || 'Error saving ride', 'error');
                        btn.text(id ? 'UPDATE RIDE INFO' : 'SAVE RIDE INFO').prop('disabled', false);
                    });
            });
        });
    </script>
@endpush
