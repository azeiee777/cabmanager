<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CabManager | Elite Fleet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
</head>

<body class="bg-gray-950 text-gray-100 font-sans flex justify-center min-h-screen">

    <div class="w-full h-screen flex flex-col md:flex-row bg-gray-950 overflow-hidden relative">

        <!-- LEFT SIDE: MARKETING -->
        <div id="marketingView"
            class="w-full md:w-1/2 lg:w-3/5 flex flex-col h-full bg-gray-950 relative z-10 transition-all duration-300">
            <nav class="p-6 md:p-10 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-amber-500 p-1.5 rounded-lg text-gray-900"><i data-lucide="car" class="w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-xl text-white">Cab<span class="text-amber-500">Manager</span></span>
                </div>
                <button onclick="openAuthView('login')" class="md:hidden text-sm font-semibold text-gray-300">Log
                    In</button>
            </nav>

            <div class="px-6 md:px-12 my-auto text-center md:text-left">
                <h1 class="text-4xl sm:text-6xl font-black text-white mb-6">Maximize Earnings.<br /><span
                        class="text-amber-500">Minimize Hassle.</span></h1>
                <p class="text-gray-400 text-base md:text-lg max-w-md mb-8 mx-auto md:mx-0">Track tolls, deadheads, and
                    actual net profit exclusively for professional cab drivers.</p>
                <button onclick="openAuthView('signup')"
                    class="md:hidden w-full bg-amber-500 text-gray-950 font-bold py-4 rounded-full text-lg">Get
                    Started</button>
            </div>
        </div>

        <!-- RIGHT SIDE: AUTH FORM -->
        <div id="authView"
            class="hidden md:flex flex-col w-full md:w-1/2 lg:w-2/5 h-full absolute md:relative inset-0 z-50 bg-gray-900 md:border-l border-gray-800 overflow-y-auto">

            <div class="p-8 md:p-12 flex flex-col justify-center min-h-full max-w-md mx-auto w-full">

                <button onclick="closeAuthView()"
                    class="md:hidden p-2 mb-6 bg-gray-800 rounded-full w-max text-gray-400"><i data-lucide="x"
                        class="w-5 h-5"></i></button>

                <!-- Default text is now set for Login -->
                <h2 class="text-3xl font-bold text-white mb-2" id="authTitle">Welcome Back</h2>
                <p class="text-gray-400 mb-8" id="authDesc">Sign in to access your dashboard.</p>

                <div id="authError"
                    class="hidden mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-start gap-3">
                    <i data-lucide="alert-circle" class="text-rose-500 w-5 h-5 mt-0.5 shrink-0"></i>
                    <p class="text-sm text-rose-400 font-medium leading-tight" id="authErrorText"></p>
                </div>

                <form id="authForm" class="space-y-5" novalidate>
                    <!-- Step 1: Identifier -->
                    <div id="step-identifier" class="space-y-5">
                        <div class="flex flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">Email
                                Address</label>
                            <input type="email" id="identifier" placeholder="e.g. driver@mail.com"
                                class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 focus:border-amber-500 outline-none">
                        </div>

                        <!-- Login Password Field (Visible by default) -->
                        <div id="loginPwdGroup" class="flex flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">Password</label>
                            <input type="password" id="login_password" placeholder="Your password"
                                class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 focus:border-amber-500 outline-none">
                        </div>

                        <!-- Cab Number Field (Hidden by default) -->
                        <div id="cabNumberGroup" class="hidden flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">Cab Number
                                (Optional)</label>
                            <input type="text" id="cab_number" placeholder="DL01AB1234"
                                class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 focus:border-amber-500 outline-none uppercase">
                        </div>

                        <!-- Main Button -->
                        <button type="submit" id="btnRequestOtp"
                            class="w-full bg-amber-500 text-gray-950 font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 text-lg">Sign
                            In</button>
                    </div>

                    <!-- Step 2: Password & OTP (Only used for Signup now) -->
                    <div id="step-verification" class="hidden space-y-5 animate-in slide-in-from-right-4">
                        <div class="bg-amber-500/5 p-4 rounded-xl border border-amber-500/20 mb-2">
                            <p class="text-xs text-amber-500 font-bold uppercase mb-1">Verifying Identity</p>
                            <p class="text-sm text-gray-300" id="verifyTargetDisplay"></p>
                        </div>
                        <div class="flex flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">Create
                                Password</label>
                            <input type="password" id="password" placeholder="Min. 6 characters"
                                class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 focus:border-amber-500 outline-none">
                        </div>
                        <div class="flex flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">Enter 6-Digit
                                OTP</label>
                            <input type="text" id="otp" maxlength="6" placeholder="000000"
                                class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 focus:border-amber-500 outline-none text-center text-2xl tracking-[0.5em] font-black">
                        </div>
                        <button type="submit" id="submitBtn"
                            class="w-full bg-amber-500 text-gray-950 font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 text-lg">Verify
                            & Create Account</button>
                        <button type="button" onclick="resetFlow()"
                            class="w-full text-xs text-gray-500 font-bold hover:text-gray-300">Change Email</button>
                    </div>
                </form>

                <div id="authToggleContainer" class="mt-8 text-center border-t border-gray-800 pt-6">
                    <p class="text-sm text-gray-400">
                        <span id="toggleText">Don't have an account?</span>
                        <button type="button" onclick="toggleMode()" id="toggleModeBtn"
                            class="text-amber-500 font-semibold ml-1">Sign Up</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Start in Login mode by default
        let isSignup = false;

        window.openAuthView = (mode) => {
            isSignup = mode === 'signup';
            if (window.innerWidth < 768) {
                $('#marketingView').hide();
                $('#authView').removeClass('hidden').addClass('flex');
            }
            updateUI();
        };

        window.closeAuthView = () => {
            $('#authView').addClass('hidden');
            $('#marketingView').show();
        };

        window.toggleMode = () => {
            isSignup = !isSignup;
            resetFlow();
            updateUI();
        };

        function updateUI() {
            $('#authTitle').text(isSignup ? 'Join the Elite Fleet' : 'Welcome Back');
            $('#authDesc').text(isSignup ? 'We\'ll send a code to verify your identity.' :
                'Sign in to access your dashboard.');

            if (isSignup) {
                // Show Signup Fields
                $('#cabNumberGroup').removeClass('hidden').addClass('flex');
                $('#loginPwdGroup').addClass('hidden').removeClass('flex');
                $('#btnRequestOtp').attr('type', 'button').text('Next: Get OTP');
            } else {
                // Show Login Fields
                $('#cabNumberGroup').addClass('hidden').removeClass('flex');
                $('#loginPwdGroup').removeClass('hidden').addClass('flex');
                $('#btnRequestOtp').attr('type', 'submit').text('Sign In');

                // Ensure we are back on step 1 if toggling from halfway through signup
                $('#step-identifier').show();
                $('#step-verification').hide();
            }

            $('#toggleText').text(isSignup ? 'Already have an account?' : "Don't have an account?");
            $('#toggleModeBtn').text(isSignup ? 'Sign In' : 'Sign Up');
        }

        window.resetFlow = () => {
            $('#step-identifier').show();
            $('#step-verification').hide();
            $('#authToggleContainer').show();
            $('#authError').addClass('hidden');
            $('#btnRequestOtp').prop('disabled', false).text(isSignup ? 'Next: Get OTP' : 'Sign In');
        };

        const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

        // Main Button Click Handler (Intercepts clicks on "Next: Get OTP" button)
        $('#btnRequestOtp').click(function(e) {
            // IF LOGIN MODE: Let the form native submit handle it (it triggers the form.submit event below)
            if (!isSignup) {
                return; // Do nothing here, allow form submission to trigger
            }

            // IF SIGNUP MODE: Prevent submission and do AJAX for OTP instead
            e.preventDefault();

            const identifier = $('#identifier').val().trim();
            if (!identifier) return showError('Please enter your Email Address.');
            if (!isValidEmail(identifier)) return showError('Please enter a valid email address.');

            const btn = $(this);
            btn.text('Sending OTP...').prop('disabled', true);

            $.post("{{ route('api.send-otp') }}", {
                    identifier
                })
                .done(function(res) {
                    $('#authError').addClass('hidden');
                    $('#step-identifier').hide();
                    $('#step-verification').removeClass('hidden').show();
                    $('#authToggleContainer').hide();
                    $('#verifyTargetDisplay').text(identifier);
                })
                .fail(err => showError(err.responseJSON.error || err.responseJSON.message || 'Failed to send OTP.'))
                .always(() => btn.text('Next: Get OTP').prop('disabled', false));
        });

        // Form Submission Logic (Handles both Login and Final Registration step)
        $('#authForm').submit(function(e) {
            e.preventDefault(); // Stop page reload

            const url = isSignup ? "{{ route('api.register') }}" : "{{ route('api.login') }}";
            const identifier = $('#identifier').val().trim();

            if (!identifier) return showError('Please enter your email.');
            if (!isValidEmail(identifier)) return showError('Please enter a valid email.');

            let data = {};

            if (isSignup) {
                const password = $('#password').val();
                const otp = $('#otp').val();
                if (!password || password.length < 6) return showError('Password must be at least 6 characters.');
                if (!otp || otp.length !== 6) return showError('Please enter the 6-digit OTP.');

                data = {
                    identifier: identifier,
                    password: password,
                    otp: otp,
                    cab_number: $('#cab_number').val()
                };
                $('#submitBtn').text('Processing...').prop('disabled', true);
            } else {
                const loginPassword = $('#login_password').val();
                if (!loginPassword) return showError('Please enter your password.');

                data = {
                    identifier: identifier,
                    password: loginPassword
                };
                $('#btnRequestOtp').text('Processing...').prop('disabled', true);
            }

            $.post(url, data)
                .done(() => window.location.href = "{{ route('dashboard.view') }}")
                .fail(err => {
                    showError(err.responseJSON.error || err.responseJSON.message || 'Authentication failed.');
                    if (isSignup) {
                        $('#submitBtn').text('Verify & Create Account').prop('disabled', false);
                    } else {
                        $('#btnRequestOtp').text('Sign In').prop('disabled', false);
                    }
                });
        });

        function showError(msg) {
            $('#authErrorText').text(msg);
            $('#authError').removeClass('hidden');
        }
    </script>
</body>

</html>
