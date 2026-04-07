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
                    },
                    animation: {
                        'cab-bounce': 'cab-bounce 0.4s alternate infinite ease-in-out',
                        'cab-drive': 'cab-drive 5s linear infinite',
                    },
                    keyframes: {
                        'cab-bounce': {
                            '0%': {
                                transform: 'translateY(0)'
                            },
                            '100%': {
                                transform: 'translateY(-3px)'
                            }
                        },
                        'cab-drive': {
                            '0%': {
                                left: '-200px'
                            },
                            '100%': {
                                left: '100%'
                            }
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
            <nav class="p-6 md:p-10 flex justify-between items-center relative z-20">
                <div class="flex items-center gap-2">
                    <div class="bg-amber-500 p-1.5 rounded-lg text-gray-900"><i data-lucide="car" class="w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-xl text-white">Cab<span class="text-amber-500">Manager</span></span>
                </div>
                <button onclick="openAuthView('login')" class="md:hidden text-sm font-semibold text-gray-300">Log
                    In</button>
            </nav>

            <div class="px-6 md:px-12 my-auto text-center md:text-left relative z-20 pb-32 md:pb-0">
                <h1 class="text-4xl sm:text-6xl font-black text-white mb-6">Maximize Earnings.<br /><span
                        class="text-amber-500">Minimize Hassle.</span></h1>
                <p class="text-gray-400 text-base md:text-lg max-w-md mb-8 mx-auto md:mx-0">Track tolls, deadheads, and
                    actual net profit exclusively for professional cab drivers.</p>
                <button onclick="openAuthView('signup')"
                    class="md:hidden w-full bg-amber-500 text-gray-950 font-bold py-4 rounded-full text-lg shadow-lg shadow-amber-500/20">Get
                    Started</button>
            </div>

            <!-- ANIMATED CAB & ROAD BACKGROUND -->
            <div
                class="absolute bottom-0 left-0 w-full h-48 pointer-events-none overflow-hidden z-10 opacity-60 md:opacity-100">
                <!-- The Cab (Now drives across the screen and bounces) -->
                <div class="absolute bottom-8 animate-cab-drive w-[140px]">
                    <div class="animate-cab-bounce relative">
                        <svg width="140" height="56" viewBox="0 0 140 56" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <!-- Cab Body -->
                            <path
                                d="M18 24L30 6H100L118 24H135C137.761 24 140 26.2386 140 29V42C140 44.7614 137.761 47 135 47H122C122 51.9706 117.971 56 113 56C108.029 56 104 51.9706 104 47H36C36 51.9706 31.9706 56 27 56C22.0294 56 18 51.9706 18 47H5C2.23858 47 0 44.7614 0 42V29C0 26.2386 2.23858 24 5 24H18Z"
                                fill="#F59E0B" />
                            <!-- Windows -->
                            <path d="M33 9H70V21H23L33 9Z" fill="#171717" />
                            <path d="M76 9H96.5L106.5 21H76V9Z" fill="#171717" />
                            <!-- Wheels -->
                            <circle cx="27" cy="47" r="7" fill="#171717" stroke="#333"
                                stroke-width="2" />
                            <circle cx="113" cy="47" r="7" fill="#171717" stroke="#333"
                                stroke-width="2" />
                            <!-- Details (Headlight & Taxi Sign) -->
                            <path d="M135 29C137.761 29 140 31.2386 140 34V37H135V29Z" fill="#FEF08A" />
                            <rect x="55" y="0" width="30" height="6" rx="2" fill="#F59E0B" />
                            <!-- Tail light -->
                            <rect x="0" y="29" width="4" height="8" fill="#EF4444" />
                        </svg>
                        <!-- Speed lines / Exhaust -->
                        <div class="absolute bottom-3 -left-12 w-8 h-1 bg-gray-600 rounded-full opacity-50"></div>
                        <div class="absolute bottom-5 -left-16 w-6 h-1 bg-gray-600 rounded-full opacity-30"></div>
                    </div>
                </div>

                <!-- The Road Base -->
                <div class="absolute bottom-4 left-0 w-full h-1 bg-gray-800"></div>
                <!-- The Static Dashed Lines -->
                <div
                    class="absolute bottom-4 left-0 w-full h-1 bg-[linear-gradient(90deg,transparent_0%,transparent_50%,#f59e0b_50%,#f59e0b_100%)] bg-[length:60px_4px] shadow-[0_0_10px_rgba(245,158,11,0.5)]">
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: AUTH FORM -->
        <div id="authView"
            class="hidden md:flex flex-col w-full md:w-1/2 lg:w-2/5 h-full absolute md:relative inset-0 z-50 bg-gray-900 md:border-l border-gray-800 overflow-y-auto">

            <div class="p-8 md:p-12 flex flex-col justify-center min-h-full max-w-md mx-auto w-full">

                <button onclick="closeAuthView()"
                    class="md:hidden p-2 mb-6 bg-gray-800 rounded-full w-max text-gray-400 hover:text-white transition-colors"><i
                        data-lucide="arrow-left" class="w-5 h-5"></i></button>

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

                        <div id="forgotPasswordHelp"
                            class="hidden rounded-xl border border-sky-500/20 bg-sky-500/5 px-4 py-3 text-sm text-sky-200">
                            Enter your email address and we will send a 6-digit OTP to reset your password.
                        </div>

                        <!-- Login Password Field (Visible by default) -->
                        <div id="loginPwdGroup" class="flex flex-col space-y-1.5">
                            <div class="flex items-center justify-between gap-3">
                                <label
                                    class="text-xs font-medium text-gray-400 uppercase tracking-widest">Password</label>
                                <button type="button" onclick="openForgotPassword()"
                                    class="text-xs font-semibold text-amber-500 hover:text-amber-400 transition-colors">
                                    Forgot Password?
                                </button>
                            </div>
                            <div class="relative">
                                <input type="password" id="login_password" placeholder="Your password"
                                    class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 pr-20 focus:border-amber-500 outline-none">
                                <button type="button" data-password-toggle data-target="#login_password"
                                    class="absolute inset-y-0 right-3 flex items-center text-xs font-semibold text-gray-400 hover:text-amber-400 transition-colors">
                                    Show
                                </button>
                            </div>
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
                            <div class="relative">
                                <input type="password" id="password" placeholder="Min. 6 characters"
                                    class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 pr-20 focus:border-amber-500 outline-none">
                                <button type="button" data-password-toggle data-target="#password"
                                    class="absolute inset-y-0 right-3 flex items-center text-xs font-semibold text-gray-400 hover:text-amber-400 transition-colors">
                                    Show
                                </button>
                            </div>
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

                    <div id="step-reset" class="hidden space-y-5 animate-in slide-in-from-right-4">
                        <div class="bg-amber-500/5 p-4 rounded-xl border border-amber-500/20 mb-2">
                            <p class="text-xs text-amber-500 font-bold uppercase mb-1">Resetting Password</p>
                            <p class="text-sm text-gray-300" id="resetTargetDisplay"></p>
                        </div>
                        <div class="flex flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">New
                                Password</label>
                            <div class="relative">
                                <input type="password" id="reset_password" placeholder="Min. 6 characters"
                                    class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 pr-20 focus:border-amber-500 outline-none">
                                <button type="button" data-password-toggle data-target="#reset_password"
                                    class="absolute inset-y-0 right-3 flex items-center text-xs font-semibold text-gray-400 hover:text-amber-400 transition-colors">
                                    Show
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-1.5">
                            <label class="text-xs font-medium text-gray-400 uppercase tracking-widest">Enter 6-Digit
                                OTP</label>
                            <input type="text" id="reset_otp" maxlength="6" placeholder="000000"
                                class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-xl px-4 py-3.5 focus:border-amber-500 outline-none text-center text-2xl tracking-[0.5em] font-black">
                        </div>
                        <button type="submit" id="resetPasswordBtn"
                            class="w-full bg-amber-500 text-gray-950 font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 text-lg">
                            Reset Password
                        </button>
                        <button type="button" onclick="resetFlow()"
                            class="w-full text-xs text-gray-500 font-bold hover:text-gray-300">Change Email</button>
                    </div>
                </form>

                <div id="authToggleContainer" class="mt-8 text-center border-t border-gray-800 pt-6">
                    <p class="text-sm text-gray-400">
                        <span id="toggleText">Don't have an account?</span>
                        <button type="button" onclick="handleFooterAction()" id="toggleModeBtn"
                            class="text-amber-500 font-semibold ml-1 hover:text-amber-400 transition-colors">Sign
                            Up</button>
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

        let isSignup = false;
        let isForgotPassword = false;

        function currentMode() {
            if (isForgotPassword) {
                return 'forgot';
            }

            return isSignup ? 'signup' : 'login';
        }

        function primaryButtonLabel() {
            const mode = currentMode();

            if (mode === 'signup') {
                return 'Next: Get OTP';
            }

            if (mode === 'forgot') {
                return 'Send Reset OTP';
            }

            return 'Sign In';
        }

        function resetStepPanels() {
            $('#step-identifier').show();
            $('#step-verification, #step-reset').hide().addClass('hidden');
            $('#authToggleContainer').show();
            $('#authError').addClass('hidden');
            $('#btnRequestOtp').prop('disabled', false).text(primaryButtonLabel());
            $('#submitBtn').prop('disabled', false).text('Verify & Create Account');
            $('#resetPasswordBtn').prop('disabled', false).text('Reset Password');
            $('#password, #otp, #reset_password, #reset_otp').val('');
        }

        window.openAuthView = (mode) => {
            isSignup = mode === 'signup';
            isForgotPassword = false;
            resetStepPanels();
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

        window.handleFooterAction = () => {
            if (isSignup || isForgotPassword) {
                isSignup = false;
                isForgotPassword = false;
            } else {
                isSignup = true;
                isForgotPassword = false;
            }

            resetStepPanels();
            updateUI();
        };

        window.openForgotPassword = () => {
            isSignup = false;
            isForgotPassword = true;
            resetStepPanels();
            updateUI();
        };

        function updateUI() {
            const mode = currentMode();
            const loginMode = mode === 'login';
            const signupMode = mode === 'signup';
            const forgotMode = mode === 'forgot';

            $('#authTitle').text(signupMode ? 'Join the Elite Fleet' : forgotMode ? 'Forgot Password' :
                'Welcome Back');
            $('#authDesc').text(signupMode ? 'We\'ll send a code to verify your identity.' :
                forgotMode ? 'Enter your email to receive a password reset code.' :
                'Sign in to access your dashboard.');

            if (signupMode) {
                $('#cabNumberGroup').removeClass('hidden').addClass('flex');
            } else {
                $('#cabNumberGroup').addClass('hidden').removeClass('flex');
            }

            if (loginMode) {
                $('#loginPwdGroup').removeClass('hidden').addClass('flex');
            } else {
                $('#loginPwdGroup').addClass('hidden').removeClass('flex');
            }

            $('#forgotPasswordHelp').toggleClass('hidden', !forgotMode);
            $('#btnRequestOtp').attr('type', loginMode ? 'submit' : 'button').text(primaryButtonLabel());

            if (forgotMode) {
                $('#toggleText').text('Remember your password?');
                $('#toggleModeBtn').text('Sign In');
            } else {
                $('#toggleText').text(signupMode ? 'Already have an account?' : "Don't have an account?");
                $('#toggleModeBtn').text(signupMode ? 'Sign In' : 'Sign Up');
            }
        }

        window.resetFlow = () => {
            resetStepPanels();
            updateUI();
        };

        $(document).on('click', '[data-password-toggle]', function() {
            const toggle = $(this);
            const input = $(toggle.data('target'));
            const currentlyVisible = input.attr('type') === 'text';

            input.attr('type', currentlyVisible ? 'password' : 'text');
            toggle.text(currentlyVisible ? 'Show' : 'Hide');
        });

        const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

        $('#btnRequestOtp').click(function(e) {
            const mode = currentMode();

            if (mode === 'login') {
                return;
            }

            e.preventDefault();

            const identifier = $('#identifier').val().trim();
            if (!identifier) return showError('Please enter your Email Address.');
            if (!isValidEmail(identifier)) return showError('Please enter a valid email address.');

            const btn = $(this);
            const url = mode === 'signup' ? "{{ route('api.send-otp') }}" :
                "{{ route('api.password.send-reset-otp') }}";
            btn.text(mode === 'signup' ? 'Sending OTP...' : 'Sending Reset OTP...').prop('disabled', true);

            $.post(url, {
                    identifier
                })
                .done(function() {
                    $('#authError').addClass('hidden');
                    $('#step-identifier').hide();
                    $('#authToggleContainer').hide();

                    if (mode === 'signup') {
                        $('#step-verification').removeClass('hidden').show();
                        $('#verifyTargetDisplay').text(identifier);
                    } else {
                        $('#step-reset').removeClass('hidden').show();
                        $('#resetTargetDisplay').text(identifier);
                    }
                })
                .fail(err => showError(err.responseJSON.error || err.responseJSON.message || 'Failed to send OTP.'))
                .always(() => btn.text(primaryButtonLabel()).prop('disabled', false));
        });

        $('#authForm').submit(function(e) {
            e.preventDefault();

            const mode = currentMode();
            const identifier = $('#identifier').val().trim();

            if (!identifier) return showError('Please enter your email.');
            if (!isValidEmail(identifier)) return showError('Please enter a valid email.');

            let data = {};
            let url = '';
            let loadingButton = null;

            if (mode === 'signup') {
                const password = $('#password').val();
                const otp = $('#otp').val();
                if (!password || password.length < 6) return showError('Password must be at least 6 characters.');
                if (!otp || otp.length !== 6) return showError('Please enter the 6-digit OTP.');

                url = "{{ route('api.register') }}";
                data = {
                    identifier: identifier,
                    password: password,
                    otp: otp,
                    cab_number: $('#cab_number').val()
                };
                loadingButton = $('#submitBtn');
                loadingButton.text('Processing...').prop('disabled', true);
            } else if (mode === 'forgot') {
                const resetPassword = $('#reset_password').val();
                const resetOtp = $('#reset_otp').val();

                if (!resetPassword || resetPassword.length < 6) {
                    return showError('New password must be at least 6 characters.');
                }

                if (!resetOtp || resetOtp.length !== 6) {
                    return showError('Please enter the 6-digit OTP.');
                }

                url = "{{ route('api.password.reset') }}";
                data = {
                    identifier: identifier,
                    password: resetPassword,
                    otp: resetOtp
                };
                loadingButton = $('#resetPasswordBtn');
                loadingButton.text('Resetting...').prop('disabled', true);
            } else {
                const loginPassword = $('#login_password').val();
                if (!loginPassword) return showError('Please enter your password.');

                url = "{{ route('api.login') }}";
                data = {
                    identifier: identifier,
                    password: loginPassword
                };
                loadingButton = $('#btnRequestOtp');
                loadingButton.text('Processing...').prop('disabled', true);
            }

            $.post(url, data)
                .done(() => window.location.href = "{{ route('dashboard.view') }}")
                .fail(err => {
                    showError(err.responseJSON.error || err.responseJSON.message || 'Authentication failed.');
                    if (mode === 'signup') {
                        $('#submitBtn').text('Verify & Create Account').prop('disabled', false);
                    } else if (mode === 'forgot') {
                        $('#resetPasswordBtn').text('Reset Password').prop('disabled', false);
                    } else {
                        $('#btnRequestOtp').text('Sign In').prop('disabled', false);
                    }
                });
        });

        function showError(msg) {
            $('#authErrorText').text(msg);
            $('#authError').removeClass('hidden');
        }

        updateUI();
    </script>
</body>

</html>
