<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
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
</body>
</html>
