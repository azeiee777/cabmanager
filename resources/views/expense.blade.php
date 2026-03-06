@extends('layouts.app')

@section('content')
<div class="pt-2">
    <h2 class="text-xl font-semibold mb-6 text-gray-100" id="pageTitle">Add Expense</h2>
    
    <form id="apiExpenseForm" class="space-y-5">
        <div class="space-y-1.5">
            <label class="text-xs font-medium text-gray-400">Date</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 [color-scheme:dark]" required>
        </div>
        
        <div class="space-y-1.5">
            <label class="text-xs font-medium text-gray-400">Amount (₹)</label>
            <input type="number" name="amount" placeholder="e.g. 1500" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500" required>
        </div>

        <div class="flex flex-col space-y-1.5">
            <label class="text-xs font-medium text-gray-400">Expense Type</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500"><i data-lucide="fuel" class="w-4 h-4"></i></div>
                <select name="type" class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-amber-500 appearance-none">
                    @foreach($categories ?? ['Fuel', 'Maintenance', 'MCD Toll', 'Paid Toll', 'Challan', 'EMI', 'Cleaning', 'Other'] as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-medium text-gray-400">Description (Optional)</label>
            <input type="text" name="description" placeholder="Detail the expense..." class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500">
        </div>

        <div class="pt-6">
            <button type="submit" id="saveExpBtn" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3.5 rounded-xl shadow-[0_0_20px_rgba(244,63,94,0.3)] transition-all active:scale-[0.98]">
                Record Expense
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

        // IF EDIT MODE: Load Data
        if(editId) {
            $('#pageTitle').text('Edit Expense Details');
            $('#saveExpBtn').text('Update Expense');
            
            $.get(`{{ url('api/expenses') }}/${editId}`, function(res) {
                const e = res.expense;
                $('input[name="date"]').val(e.date);
                $('input[name="amount"]').val(e.amount);
                $('select[name="type"]').val(e.type);
                $('input[name="description"]').val(e.description);
                $('#apiExpenseForm').data('edit-id', editId);
            }).fail(function() {
                showAlert('Failed to load expense details.', 'error');
            });
        }

        // Handle Save or Update Submission
        $('#apiExpenseForm').submit(function(e) {
            e.preventDefault();
            const id = $(this).data('edit-id');
            const url = id ? `{{ url('api/expenses') }}/${id}` : "{{ route('api.expenses.store') }}";
            
            const btn = $('#saveExpBtn');
            btn.text('Saving...').prop('disabled', true);
            
            let formData = $(this).serialize();
            if (id) {
                formData += '&_method=PUT';
            }
            
            $.post(url, formData)
                .done(function(res) {
                    showAlert(res.message, 'success');
                    setTimeout(() => window.location.href = "{{ route('dashboard.view') }}", 1000);
                })
                .fail(function(err) {
                    showAlert(err.responseJSON.message || 'Error saving expense', 'error');
                    btn.text(id ? 'Update Expense' : 'Record Expense').prop('disabled', false);
                });
        });
    });
</script>
@endpush