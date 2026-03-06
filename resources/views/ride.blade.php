@extends('layouts.app')

@section('content')
    <div class="pt-2">
        <h2 class="text-xl font-semibold mb-6 text-gray-100" id="pageTitle">Add Income (Ride)</h2>

        <form id="apiRideForm" class="space-y-4">
            <!-- Date and Fare: Stacked on mobile, 2 cols on desktop -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 [color-scheme:dark]"
                        required>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Total Fare (₹)</label>
                    <input type="number" name="fare" placeholder="e.g. 500"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500"
                        required>
                </div>
            </div>

            <!-- KM and Deadhead: Stacked on mobile, 2 cols on desktop -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Ride KM</label>
                    <input type="number" name="km" placeholder="Distance"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500"
                        required>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Next Booking KM</label>
                    <input type="number" name="deadhead_km" value="3"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500"
                        required>
                </div>
            </div>

            <div class="space-y-4 pt-2 border-t border-gray-800">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Origin</label>
                    <input type="text" name="origin" placeholder="Pickup location"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Destination</label>
                    <input type="text" name="destination" placeholder="Drop location"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <!-- Tolls: Stacked on mobile, 2 cols on desktop to prevent label overlap -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-800">
                <div class="space-y-1.5">
                    <label class="text-[11px] sm:text-xs font-medium text-gray-400">MCD Toll Collected (₹)</label>
                    <input type="number" name="mcd_toll" value="0"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] sm:text-xs font-medium text-gray-400">Paid Toll Collected (₹)</label>
                    <input type="number" name="paid_toll" value="0"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" id="saveRideBtn"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold py-4 rounded-xl shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all active:scale-[0.98]">
                    Save Ride Details
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
                $('#saveRideBtn').text('Update Ride Details');

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
                btn.text('Saving...').prop('disabled', true);

                let formData = $(this).serialize();
                if (id) formData += '&_method=PUT';

                $.post(url, formData)
                    .done(function(res) {
                        showAlert(res.message, 'success');
                        setTimeout(() => window.location.href = "{{ route('dashboard.view') }}", 1000);
                    })
                    .fail(function(err) {
                        showAlert(err.responseJSON.message || 'Error saving ride', 'error');
                        btn.text(id ? 'Update Ride Details' : 'Save Ride Details').prop('disabled',
                            false);
                    });
            });
        });
    </script>
@endpush
