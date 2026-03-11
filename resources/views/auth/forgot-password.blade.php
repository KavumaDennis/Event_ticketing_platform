<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply flex items-center justify-center h-screen p-4">
    <div class="w-full max-w-md bg-green-400/10 border border-green-400/10 p-8 rounded-2xl shadow-2xl backdrop-blur-xl">
        <div class="text-center mb-8">
            <p class="text-white/70 mb-2">AKAVAAKO</p>
            <h1 class="text-2xl font-bold text-white mb-2">Forgot Password?</h1>
            <p class="text-white/60 text-sm font-mono">Enter your email to receive a reset link</p>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <ul class="p-4 mb-4 bg-red-100 rounded-xl">
                @foreach($errors->all() as $error)
                    <li class="text-red-500 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            <div class="flex flex-col gap-2">
                <label class="text-white/60 font-medium ml-1 text-sm">Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required class="p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />
            </div>

            <button type="submit" class="w-full p-3 bg-black/80 text-white/80 font-medium font-mono text-sm border border-green-400/10 rounded-3xl hover:bg-black/90 transition-all">
                Send Reset Link
            </button>

            <div class="text-center">
                <a href="{{ route('show.login') }}" class="text-white/70 underline text-xs font-mono font-medium">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>
