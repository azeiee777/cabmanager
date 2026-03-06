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
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 4px;
        }

        .animate-drive {
            animation: driveCar 4s linear infinite;
        }

        @keyframes driveCar {
            0% {
                transform: translateX(-60px);
            }

            100% {
                transform: translateX(120vw);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 font-sans flex justify-center min-h-screen">

    <div class="w-full h-screen flex flex-col md:flex-row bg-gray-950 overflow-hidden relative">

        <!-- LEFT SIDE: MARKETING VIEW -->
        <div id="marketingView"
            class="w-full md:w-1/2 lg:w-3/5 flex flex-col h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-900 via-gray-950 to-black overflow-y-auto custom-scrollbar relative z-10 transition-all duration-300">

            <nav class="flex justify-between items-center p-6 md:p-10 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="bg-amber-500 p-1.5 rounded-lg text-gray-900">
                        <i data-lucide="car" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <span class="font-bold text-xl tracking-wide text-white">Cab<span
                            class="text-amber-500">Manager</span></span>
                </div>
                <button id="navLoginBtn"
                    class="md:hidden text-sm font-semibold text-gray-300 hover:text-white transition-colors">
                    Log In
                </button>
            </nav>

            <div
                class="px-6 md:px-12 pt-6 pb-10 flex flex-col items-center md:items-start text-center md:text-left relative z-10 my-auto">
                <div
                    class="absolute top-10 left-1/2 md:left-24 -translate-x-1/2 md:-translate-x-0 w-48 h-48 bg-amber-500/20 rounded-full blur-[80px] pointer-events-none animate-pulse-glow">
                </div>

                <div
                    class="inline-flex items-center gap-1.5 bg-gray-800/60 border border-gray-700/50 rounded-full px-3 py-1 mb-6 backdrop-blur-sm shadow-lg">
                    <i data-lucide="star" class="text-amber-500 w-3 h-3 fill-amber-500"></i>
                    <span class="text-[10px] md:text-xs font-medium text-gray-300 uppercase tracking-wider">Rated #1 by
                        Chauffeurs</span>
                </div>

                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-[1.1]">
                    Maximize Earnings.<br />
                    <span
                        class="bg-gradient-to-r from-amber-200 via-amber-400 to-amber-600 bg-clip-text text-transparent">
                        Minimize Hassle.
                    </span>
                </h1>

                <p
                    class="text-gray-400 text-sm sm:text-base md:text-lg max-w-[320px] md:max-w-[450px] mb-8 leading-relaxed">
                    The all-in-one financial dashboard designed exclusively for professional cab drivers. Track tolls,
                    deadheads, and actual net profit.
                </p>

                <button id="heroSignupBtn"
                    class="md:hidden w-full px-8 py-4 bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold rounded-full shadow-[0_0_30px_rgba(245,158,11,0.4)] transition-all active:scale-[0.98] flex items-center justify-center gap-2 text-lg">
                    Get Started for Free <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="relative h-16 w-full overflow-hidden border-y border-gray-800 bg-gray-900/50 mt-auto">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-full border-t-2 border-dashed border-gray-700"></div>
                </div>
                <div class="absolute bottom-4 animate-drive drop-shadow-[0_0_10px_rgba(245,158,11,0.5)]"><i
                        data-lucide="car" class="text-amber-500 w-8 h-8 fill-amber-500/20"></i></div>
            </div>
        </div>

        <!-- RIGHT SIDE: AUTH FORM VIEW -->
        <div id="authView"
            class="hidden md:flex flex-col w-full md:w-1/2 lg:w-2/5 h-full absolute md:relative inset-0 z-50 md:z-20 bg-gray-950 md:bg-gray-900 md:border-l border-gray-800 overflow-y-auto custom-scrollbar shadow-2xl animate-in slide-in-from-bottom-4 md:animate-none">

            <div
                class="absolute top-0 inset-x-0 h-64 bg-gradient-to-b from-amber-500/10 to-transparent pointer-events-none hidden md:block">
            </div>

            <div class="p-8 md:p-12 relative z-10 flex flex-col justify-center h-full max-w-md mx-auto w-full my-auto">

                <button id="closeAuthBtn"
                    class="md:hidden p-2 -ml-2 mb-6 bg-gray-900 rounded-full border border-gray-800 text-gray-400 hover:text-white transition-colors w-max shadow-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <h2 class="text-3xl md:text-4xl font-bold text-white mb-2" id="authTitle">Join the Elite Fleet</h2>
                <p class="text-gray-400 mb-8" id="authDesc">Start tracking your earnings like a pro in seconds.</p>

                <div id="authError"
                    class="hidden mb-6 p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-start gap-3">
                    <i data-lucide="alert-circle" class="text-rose-500 shrink-0 mt-0.5 w-5 h-5"></i>
                    <p class="text-sm text-rose-400 font-medium leading-tight" id="authErrorText"></p>
                </div>

                <form id="authForm" class="space-y-5">
                    <div class="flex flex-col space-y-1.5 w-full">
                        <label class="text-xs font-medium text-gray-400">Email or 10-Digit Mobile Number</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-gray-500 pointer-events-none"><i data-lucide="user"
                                    class="w-4 h-4"></i></div>
                            <input type="text" id="identifier" placeholder="e.g. 9876543210 or driver@mail.com"
                                class="w-full bg-gray-950 md:bg-gray-900 border border-gray-800 md:border-gray-700 text-gray-200 rounded-xl pl-10 pr-4 py-3.5 focus:outline-none focus:border-amber-500 transition-colors"
                                required>
                        </div>
                    </div>

                    <div id="cabNumberGroup" class="flex flex-col space-y-1.5 w-full">
                        <label class="text-xs font-medium text-gray-400">Cab Number (Optional)</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-gray-500 pointer-events-none"><i data-lucide="car"
                                    class="w-4 h-4"></i></div>
                            <input type="text" id="cab_number" placeholder="e.g. DL01AB1234 or 21BH1234A"
                                class="w-full bg-gray-950 md:bg-gray-900 border border-gray-800 md:border-gray-700 text-gray-200 rounded-xl pl-10 pr-4 py-3.5 focus:outline-none focus:border-amber-500 transition-colors uppercase">
                        </div>
                        <p class="text-[10px] text-gray-500">Supports standard formats and BH series without spaces.</p>
                    </div>

                    <div class="flex flex-col space-y-1.5 w-full">
                        <label class="text-xs font-medium text-gray-400">Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-gray-500 pointer-events-none"><i data-lucide="lock"
                                    class="w-4 h-4"></i></div>
                            <input type="password" id="password" placeholder="Min. 6 characters"
                                class="w-full bg-gray-950 md:bg-gray-900 border border-gray-800 md:border-gray-700 text-gray-200 rounded-xl pl-10 pr-4 py-3.5 focus:outline-none focus:border-amber-500 transition-colors"
                                required minlength="6">
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold py-4 rounded-xl mt-6 shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all active:scale-[0.98] text-lg">Create
                        Free Account</button>
                </form>

                <div class="mt-8 text-center border-t border-gray-800 pt-6">
                    <p class="text-sm text-gray-400">
                        <span id="toggleText">Already have an account?</span>
                        <button type="button" id="toggleMode"
                            class="text-amber-500 font-semibold hover:text-amber-400 transition-colors ml-1">Sign
                            In</button>
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

        let isSignup = true;

        // Auto-format Cab Number
        $('#cab_number').on('input', function() {
            let val = $(this).val().replace(/\s+/g, '').toUpperCase();
            $(this).val(val);
            $('#authError').addClass('hidden');
        });

        // Adaptive toggle
        function openAuthView(mode) {
            isSignup = mode === 'signup';
            if (window.innerWidth < 768) {
                $('#marketingView').addClass('hidden');
                $('#authView').removeClass('hidden').addClass('flex');
            }
            updateAuthUI();
        }

        $('#navLoginBtn').click(() => openAuthView('login'));
        $('#heroSignupBtn, #bottomSignupBtn').click(() => openAuthView('signup'));

        $('#closeAuthBtn').click(() => {
            if (window.innerWidth < 768) {
                $('#authView').addClass('hidden').removeClass('flex');
                $('#marketingView').removeClass('hidden');
            }
            $('#authError').addClass('hidden');
        });

        $('#toggleMode').click(function() {
            isSignup = !isSignup;
            updateAuthUI();
        });

        function updateAuthUI() {
            $('#authTitle').text(isSignup ? 'Join the Elite Fleet' : 'Welcome Back');
            $('#authDesc').text(isSignup ? 'Start tracking your earnings like a pro in seconds.' :
                'Sign in to access your financial dashboard.');
            $('#submitBtn').text(isSignup ? 'Create Free Account' : 'Sign In');
            $('#cabNumberGroup').toggle(isSignup);
            $('#toggleText').text(isSignup ? 'Already have an account?' : "Don't have an account?");
            $('#toggleMode').text(isSignup ? 'Sign In' : 'Sign Up');
            $('#authError').addClass('hidden');
        }

        // Frontend Validation
        function validateAuthInput() {
            const identifier = $('#identifier').val();
            const cabNumber = $('#cab_number').val();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileRegex = /^[6-9]\d{9}$/; // Indian mobile numbers
            const cabRegex = /^([A-Z]{2}[0-9]{1,2}[A-Z]{1,2}[0-9]{4})|([0-9]{2}BH[0-9]{4}[A-Z]{1,2})$/;

            if (!emailRegex.test(identifier) && !mobileRegex.test(identifier)) {
                return "Please enter a valid email address or a 10-digit mobile number.";
            }

            if (isSignup && cabNumber && !cabRegex.test(cabNumber)) {
                return "Please enter a valid Indian vehicle number (e.g., UP32AB1234 or 21BH1234A).";
            }

            return null; // Passes validation
        }

        $('#authForm').submit(function(e) {
            e.preventDefault();

            const validationError = validateAuthInput();
            if (validationError) {
                $('#authErrorText').text(validationError);
                $('#authError').removeClass('hidden');
                return;
            }

            const btn = $('#submitBtn');
            const originalText = btn.text();
            btn.text('Processing...').prop('disabled', true);

            const url = isSignup ? "{{ route('api.register') }}" : "{{ route('api.login') }}";
            const data = {
                identifier: $('#identifier').val(),
                password: $('#password').val(),
                cab_number: $('#cab_number').val()
            };

            $.post(url, data)
                .done(function() {
                    window.location.href = "{{ route('dashboard.view') }}";
                })
                .fail(function(err) {
                    btn.text(originalText).prop('disabled', false);
                    let errorMessage = 'Authentication failed.';

                    if (err.responseJSON && err.responseJSON.errors) {
                        errorMessage = Object.values(err.responseJSON.errors)[0][0];
                    } else if (err.responseJSON && err.responseJSON.error) {
                        errorMessage = err.responseJSON.error;
                    }

                    $('#authError').removeClass('hidden');
                    $('#authErrorText').text(errorMessage);
                });
        });

        $('#identifier, #password').on('input', function() {
            $('#authError').addClass('hidden');
        });
    </script>
</body>

</html>
