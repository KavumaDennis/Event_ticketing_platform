<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        .auth-slide {
            opacity: 0;
            transform: scale(1.06) translateY(8px);
            transition: opacity 1200ms cubic-bezier(0.22, 1, 0.36, 1), transform 2400ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }
        .auth-slide.is-active {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        .auth-dots {
            position: absolute;
            bottom: 18px;
            right: 18px;
            display: flex;
            gap: 8px;
            z-index: 20;
        }
        .auth-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.35);
            transition: width 350ms ease, background 350ms ease, box-shadow 350ms ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .auth-dot.is-active {
            width: 24px;
            background: rgba(251, 146, 60, 0.9);
            box-shadow: 0 0 12px rgba(251, 146, 60, 0.35);
        }
        .pw-toggle-wrap {
            position: relative;
        }
        .pw-toggle-btn {
            position: absolute;
            right: 0.3rem;
            top: 70%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: rgba(251, 146, 60, 0.8);
            border: 1px solid rgba(34, 197, 94, 0.15);
            transition: background 200ms ease, color 200ms ease, transform 200ms ease;
        }
        .pw-toggle-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            color: rgba(251, 146, 60, 1);
            transform: translateY(-50%) scale(1.05);
        }
        .pw-toggle-input {
            padding-right: 3rem !important;
        }
    </style>
</head>
<body class="w-full">
    <div class="grid md:grid-cols-6 lg:grid-cols-5 w-full h-screen overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-purple-400/10">
        <div class="col-span-6 md:col-span-3 lg:col-span-2 w-full p-1 h-screen flex flex-col justify-between">
            <div class="bg-green-400/10 border border-green-400/10 p-5 h-full flex items-center">
                <form action="{{ route('signup') }}" method="POST" class="flex flex-col items-center gap-5 text-white/30 w-full">
                    @csrf
                    <input type="hidden" name="ref" value="{{ request('ref') }}">
                    @if(session('error'))
                    <div class="px-4 py-2 bg-red-500/20 border border-red-500 text-red-100 rounded-xl mb-4 text-sm w-full">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="px-4 py-2 bg-green-500/20 border border-green-500 text-green-100 rounded-xl mb-4 text-sm w-full">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="flex gap-2 items-center justify-center text-xs w-fit p-1 bg-orange-400/60 rounded-2xl">
                        <p class="font-medium text-black/80 ml-1">Have an account?</p>
                        <a href="{{ route('show.login') }}" class="bg-black rounded-xl p-1 px-2 text-orange-400/80 font-medium font-mono">Log in here</a>
                    </div>


                    <div class="grid grid-cols-2 w-full gap-2">
                        <div class="flex flex-col gap-2">
                            <label for="first_name" class="text-white/60 font-medium ml-1 text-sm">First name</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @error('first_name') <p class="text-red-400 text-xs ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="last_name" class="text-white/60 font-medium ml-1 text-sm">Last name</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @error('last_name') <p class="text-red-400 text-xs ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 w-full">
                        <label for="email" class="text-white/60 font-medium ml-1 text-sm">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        @error('email') <p class="text-red-400 text-xs ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-2 w-full">
                        <label for="password" class="text-white/60 font-medium ml-1 text-sm">Password</label>
                        <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Enter your password" class="p-3 wp-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />

                        @error('password')
                        <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                        @enderror
                    </div>


                  <div class="flex flex-col gap-2 w-full">
    <label for="password_confirmation" class="text-white/60 font-medium ml-1 text-sm">Confirm Password</label>
    
    <input 
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        autocomplete="new-password"
        placeholder="Re-enter your password"
        class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"
    />

    @error('password_confirmation')
        <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
    @enderror
</div>


                    <button class="w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl text-white/70 text-sm font-mono font-medium">Sign Up</button>

                    <div class="flex justify-between w-full">
                        <a href="{{ route('google.login') }}" class="p-1 w-full flex justify-center items-center bg-orange-400/60 border border-green-400/10 rounded-3xl gap-2 hover:bg-orange-400/80 transition-all">
                            <span class="p-1 relative flex items-center pr-3 text-black/90 after:absolute after:right-0 after:h-3 after:w-1 after:rounded-lg after:bg-black/90">
                                <i class="fa-brands fa-google"></i>
                            </span>
                            <span class="text-sm text-black/90 font-medium font-mono mr-2">Sign in with google</span>
                        </a>
                    </div>

                    <p class="text-orange-400/60 font-medium text-sm text-center w-[60%]">
                        Signing up for an Akavako account means you agree to the
                        <a href="#" class="text-white/60 underline">Privacy Policy</a> and
                        <a href="#" class="text-white/60 underline">Terms and Conditions</a>.
                    </p>
                </form>
            </div>
        </div>

        <div class="col-span-3 hidden md:block w-full h-full relative p-1">
            <div class="w-full h-full relative overflow-hidden rounded-3xl">
                <div class="absolute inset-0" data-auth-slider>
                    <img src="{{ asset('img1.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 1">
                    <img src="{{ asset('img2.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 2">
                    <img src="{{ asset('img4.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 3">
                    <img src="{{ asset('img5.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 4">
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/80"></div>
                <div class="auth-dots" aria-hidden="true"></div>
                <div class="relative z-10 w-full h-full">
                    <!-- right-side hero content -->
                </div>
            </div>
        </div>
    </div>
<script>
document.querySelectorAll('[data-auth-slider]').forEach((slider) => {
    const slides = Array.from(slider.querySelectorAll('.auth-slide'));
    if (!slides.length) return;

    const dotsWrap = slider.parentElement.querySelector('.auth-dots');
    let dots = [];
    if (dotsWrap) {
        dotsWrap.innerHTML = '';
        dots = slides.map((_, i) => {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'auth-dot';
            dot.addEventListener('click', () => {
                index = i;
                show(index, true);
            });
            dotsWrap.appendChild(dot);
            return dot;
        });
    }

    let index = 0;
    let timer = null;

    const show = (i, manual = false) => {
        slides.forEach((img, idx) => img.classList.toggle('is-active', idx === i));
        dots.forEach((dot, idx) => dot.classList.toggle('is-active', idx === i));
        if (manual) {
            clearInterval(timer);
            timer = setInterval(next, 4800);
        }
    };

    const next = () => {
        index = (index + 1) % slides.length;
        show(index);
    };

    show(index);
    timer = setInterval(next, 4800);
});

function initPasswordToggles(root = document) {
    const inputs = Array.from(root.querySelectorAll('input[type="password"]'));
    inputs.forEach((input) => {
        if (input.dataset.pwToggleAttached === 'true') return;
        input.dataset.pwToggleAttached = 'true';

        const parent = input.parentElement;
        if (!parent) return;

        parent.classList.add('pw-toggle-wrap');
        input.classList.add('pw-toggle-input');

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'pw-toggle-btn';
        btn.setAttribute('aria-label', 'Show password');
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696A10.75 10.75 0 0 1 21.938 12a1 1 0 0 1 0 .696A10.75 10.75 0 0 1 2.062 12.348Z"/><circle cx="12" cy="12" r="3"/></svg>';

        btn.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            btn.innerHTML = isHidden
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 2 20 20"/><path d="M10.73 5.08A10.72 10.72 0 0 1 21.94 11.65a1 1 0 0 1 0 .7 10.74 10.74 0 0 1-1.82 2.78"/><path d="M6.06 6.06A10.75 10.75 0 0 0 2.06 11.65a1 1 0 0 0 0 .7 10.74 10.74 0 0 0 1.82 2.78"/><path d="M9.5 9.5a3 3 0 0 1 4 4"/></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696A10.75 10.75 0 0 1 21.938 12a1 1 0 0 1 0 .696A10.75 10.75 0 0 1 2.062 12.348Z"/><circle cx="12" cy="12" r="3"/></svg>';
        });

        parent.appendChild(btn);
    });
}
document.addEventListener('DOMContentLoaded', () => initPasswordToggles());
</script>
</body>
</html>
