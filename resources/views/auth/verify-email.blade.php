<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fe; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; max-width: 450px; width: 100%; }
        .card-body { padding: 40px; text-align: center; }
        .icon-box { width: 80px; height: 80px; background: #e0e7ff; color: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 32px; }
        h2 { font-weight: 700; color: #1e293b; margin-bottom: 12px; }
        p { color: #64748b; line-height: 1.6; margin-bottom: 24px; }
        .btn-primary { background: #4f46e5; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; width: 100%; transition: all 0.3s; }
        .btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .btn-link { color: #64748b; text-decoration: none; font-size: 14px; margin-top: 16px; display: inline-block; }
        .btn-link:hover { color: #4f46e5; }
        .alert { border-radius: 8px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <div class="icon-box">
                <i class="bi bi-envelope-check">✉️</i>
            </div>
            <h2>Verify your email</h2>
            <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link">Log Out</button>
            </form>
        </div>
    </div>
</body>
</html>
