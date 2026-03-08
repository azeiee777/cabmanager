@extends('layouts.app')

@section('content')
    <div class="pt-2 animate-in fade-in slide-in-from-bottom-4 duration-300">
        <div class="flex items-center gap-2 mb-6">
            <div class="h-6 w-1 bg-pink-500 rounded-full"></div>
            <h2 class="text-xl font-semibold text-gray-100" id="pageTitle">Add Expense</h2>
        </div>

        <form id="apiExpenseForm" class="space-y-6">
            <!-- Date Input -->
            <div class="space-y-1.5">
                <label class="text-xs font-medium text-gray-500 ml-1 uppercase tracking-wider">Date</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-lg pointer-events-none">📅</span>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-900 border border-gray-800 text-gray-200 rounded-xl h-14 pl-12 pr-4 focus:ring-2 focus:ring-pink-500 focus:border-transparent appearance-none leading-none"
                        style="display: flex; align-items: center;" required>
                </div>
            </div>

            <!-- Amount Input -->
            <div class="space-y-1.5">
                <label class="text-xs font-medium text-gray-500 ml-1 uppercase tracking-wider">Amount (₹)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₹</span>
                    <input type="number" name="amount" placeholder="e.g. 1500"
                        class="w-full bg-gray-900 border border-gray-800 text-gray-200 rounded-xl h-14 pl-10 pr-4 focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        required>
                </div>
            </div>

            <!-- Expense Category -->
            <div class="flex flex-col space-y-1.5">
                <label class="text-xs font-medium text-gray-500 ml-1 uppercase tracking-wider">Expense Type</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500">
                        <i data-lucide="fuel" class="w-5 h-5"></i>
                    </div>
                    <select name="type"
                        class="w-full bg-gray-900 border border-gray-800 text-gray-200 rounded-xl h-14 pl-12 pr-10 focus:ring-2 focus:ring-pink-500 appearance-none">
                        @foreach ($categories ?? ['Fuel', 'Maintenance', 'MCD Toll', 'Paid Toll', 'Challan', 'EMI', 'Cleaning', 'Other'] as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-600">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label class="text-xs font-medium text-gray-500 ml-1 uppercase tracking-wider">Description
                    (Optional)</label>
                <textarea name="description" placeholder="Detail the expense..."
                    class="w-full bg-gray-900 border border-gray-800 text-gray-200 rounded-2xl p-4 focus:ring-2 focus:ring-pink-500 h-32 custom-scrollbar outline-none"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" id="saveExpBtn"
                    class="w-full bg-pink-600 hover:bg-pink-700 text-white font-black py-5 rounded-2xl shadow-xl transition-all active:scale-95 text-lg">
                    RECORD EXPENSE
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            lucide.createIcons();
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');

            if (editId) {
                $('#pageTitle').text('Edit Expense Details');
                $('#saveExpBtn').text('UPDATE EXPENSE');

                $.get(`{{ url('api/expenses') }}/${editId}`, function(res) {
                    const e = res.expense;
                    $('input[name="date"]').val(e.date);
                    $('input[name="amount"]').val(e.amount);
                    $('select[name="type"]').val(e.type);
                    $('textarea[name="description"]').val(e.description);
                    $('#apiExpenseForm').data('edit-id', editId);
                }).fail(function() {
                    showAlert('Failed to load expense details.', 'error');
                });
            }

            $('#apiExpenseForm').submit(function(e) {
                e.preventDefault();
                const id = $(this).data('edit-id');
                const url = id ? `{{ url('api/expenses') }}/${id}` : "{{ route('api.expenses.store') }}";
                const btn = $('#saveExpBtn');
                btn.text('PROCESSING...').prop('disabled', true);

                let formData = $(this).serialize();
                if (id) formData += '&_method=PUT';

                $.post(url, formData)
                    .done(function(res) {
                        showAlert(res.message, 'success');
                        setTimeout(() => window.location.href = "{{ route('dashboard.view') }}", 100);
                    })
                    .fail(function(err) {
                        showAlert(err.responseJSON.message || 'Error saving expense', 'error');
                        btn.text(id ? 'UPDATE EXPENSE' : 'RECORD EXPENSE').prop('disabled', false);
                    });
            });
        });
    </script>
@endpush
