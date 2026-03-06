@extends('layouts.app')

@section('content')
    <div class="pt-2 space-y-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-100">Settings</h2>
        </div>

        <!-- Account Info -->
        <section class="bg-gray-800/40 border border-gray-700 p-4 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-700 rounded-full"><i data-lucide="user" class="w-5 h-5 text-gray-300"></i></div>
                <div>
                    <p class="text-xs text-gray-400">Logged in as</p>
                    <p class="font-medium text-sm text-gray-200" id="userIdentifier">Loading...</p>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <h3 class="text-sm font-semibold text-amber-500 uppercase tracking-wider">Profile</h3>

            <div id="validationError"
                class="hidden mb-4 p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-start gap-3">
                <i data-lucide="alert-circle" class="text-rose-500 shrink-0 w-5 h-5"></i>
                <p class="text-sm text-rose-400 font-medium" id="validationErrorText"></p>
            </div>

            <form id="apiSettingsForm" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Cab Number</label>
                    <input type="text" id="cab_number" name="cab_number" placeholder="e.g. DL01AB1234 or 21BH1234A"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 uppercase"
                        required>
                    <p class="text-[10px] text-gray-500 mt-1">Enter without spaces. Supports standard state codes (DL, UP,
                        HR) and BH series.</p>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">App Access PIN</label>
                    <input type="text" id="pin" name="pin" maxlength="4" pattern="\d{4}"
                        placeholder="4-digit PIN"
                        class="w-full bg-gray-900 border border-gray-700 text-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500"
                        required>
                </div>
                <button type="submit" id="saveSetBtn"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold py-3.5 rounded-xl shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all mt-2 active:scale-[0.98]">
                    Save Settings
                </button>
            </form>
        </section>

        <div class="pt-6 border-t border-gray-800">
            <button id="logoutBtn"
                class="w-full flex justify-center items-center gap-2 py-3.5 rounded-xl bg-rose-500/10 text-rose-500 font-bold hover:bg-rose-500/20 transition-colors active:scale-[0.98]">
                <i data-lucide="log-out" class="w-5 h-5"></i> Sign Out of Account
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Load existing settings via API
            $.get("{{ route('api.settings.data') }}", function(res) {
                $('#userIdentifier').text(res.user.identifier);
                $('#cab_number').val(res.user.cab_number);
                $('#pin').val(res.user.pin);
            });

            // Format Cab Number on input (remove spaces, uppercase)
            $('#cab_number').on('input', function() {
                let val = $(this).val().replace(/\s+/g, '').toUpperCase();
                $(this).val(val);
                $('#validationError').addClass('hidden');
            });

            // Restrict PIN to numbers only
            $('#pin').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                $('#validationError').addClass('hidden');
            });

            // Client-side validation function
            function validateInput() {
                const cabNumber = $('#cab_number').val();
                const pin = $('#pin').val();

                // Regex for standard formats (e.g., UP32AB1234) OR BH Series (e.g., 21BH1234A)
                const cabRegex = /^([A-Z]{2}[0-9]{1,2}[A-Z]{1,2}[0-9]{4})|([0-9]{2}BH[0-9]{4}[A-Z]{1,2})$/;

                if (!cabRegex.test(cabNumber)) {
                    return "Please enter a valid Indian vehicle number (e.g., UP32AB1234 or 21BH1234A).";
                }
                if (pin.length !== 4) {
                    return "App Access PIN must be exactly 4 digits.";
                }
                return null; // No errors
            }

            // Update settings via API
            $('#apiSettingsForm').submit(function(e) {
                e.preventDefault();

                const validationError = validateInput();
                if (validationError) {
                    $('#validationErrorText').text(validationError);
                    $('#validationError').removeClass('hidden');
                    return;
                }

                const btn = $('#saveSetBtn');
                btn.text('Saving...').prop('disabled', true);

                $.post("{{ route('api.settings.update') }}", $(this).serialize())
                    .done(function(res) {
                        showAlert(res.message, 'success');
                        $('#headerCabNumber').text(res.user.cab_number);
                        // Update all elements with the class cab-number-display across the app shell
                        $('.cab-number-display').text(res.user.cab_number);
                        btn.text('Save Settings').prop('disabled', false);
                    })
                    .fail(function(err) {
                        let errorMessage = 'Error saving settings';
                        if (err.responseJSON && err.responseJSON.errors) {
                            // Extract first validation error from Laravel
                            errorMessage = Object.values(err.responseJSON.errors)[0][0];
                        } else if (err.responseJSON && err.responseJSON.message) {
                            errorMessage = err.responseJSON.message;
                        }

                        $('#validationErrorText').text(errorMessage);
                        $('#validationError').removeClass('hidden');
                        btn.text('Save Settings').prop('disabled', false);
                    });
            });

            // Handle Logout
            $('#logoutBtn').click(function() {
                $.post("{{ route('api.logout') }}", function() {
                    window.location.href = "{{ route('login.view') }}";
                });
            });
        });
    </script>
@endpush
