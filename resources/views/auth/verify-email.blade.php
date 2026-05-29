<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            /* height: 100vh;
            display: flex;
            position: relative;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            background-attachment: fixed;
            background-blend-mode: multiply;
            background-image: url("/public/bg-img.png"); */
            background-color: rgba(0, 0, 0, 0.856);
            
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: #05df721c ;
            border: 1px solid #05df7246 ;
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .card-body {
            padding: 40px;
            text-align: center;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
        }

        h3 {
            font-weight: 700;
            color: oklch(75% 0.183 55.934);
            margin-bottom: 12px;
        }

        p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .btn-primary {
            background: #4f46e5;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .btn-link {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            margin-top: 16px;
            display: inline-block;
        }

        .btn-link:hover {
            color: #4f46e5;
        }

        .alert {
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>

<body class="relative flex items-center justify-center h-screen bg-black/90 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply">
    <div class="card bg-green-400/20">
        <div class="card-body">
            <div class="icon-box">
                <i class="bi bi-envelope-check">✉️</i>
            </div>
            <h3>Verify your email</h3>
            <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                link we just emailed to you?</p>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="p-2.5 text-center uppercase bg-orange-400 w-full rounded-xl">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link">Log Out</button>
            </form>
        </div>
    </div>
</body>

</html>
