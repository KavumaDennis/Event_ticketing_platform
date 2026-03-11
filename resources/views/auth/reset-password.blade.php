<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
    <style>
        .pw-toggle-wrap {
            position: relative;
        }
        .pw-toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 999px;
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
<body class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-green-400/10 border border-green-400/10 p-8 rounded-2xl shadow-2xl backdrop-blur-xl">
        <div class="text-center mb-8">
            <p class="text-white/70 mb-2">AKAVAAKO</p>
            <h1 class="text-2xl font-bold text-white mb-2">Set New Password</h1>
            <p class="text-white/60 text-sm font-mono">Create a strong password for your account</p>
        </div>

        @if($errors->any())
            <ul class="p-4 mb-4 bg-red-100 rounded-xl">
                @foreach($errors->all() as $error)
                    <li class="text-red-500 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="flex flex-col gap-2">
                <label class="text-white/60 font-medium ml-1 text-sm">Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required class="p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-white/60 font-medium ml-1 text-sm">New Password</label>
                <input type="password" name="password" autocomplete="new-password" placeholder="Min 8 characters" required class="p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-white/60 font-medium ml-1 text-sm">Confirm Password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repeat password" required class="p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />
            </div>

            <button type="submit" class="w-full p-3 bg-black/80 text-white/80 font-medium font-mono text-sm border border-green-400/10 rounded-3xl hover:bg-black/90 transition-all">
                Update Password
            </button>
        </form>
    </div>
<script>
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
