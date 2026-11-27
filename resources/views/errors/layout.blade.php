{{-- Halaman galat mandiri (tanpa Vite/DB/session agar tetap tampil saat aplikasi bermasalah) --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mivent | @yield('title')</title>
<link rel="icon" href="{{ asset('mivent_assets/logoColor.png') }}">
<style>
    *{box-sizing:border-box}
    body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:Poppins,ui-sans-serif,system-ui,sans-serif;background:linear-gradient(180deg,#EDF4FB 0%,#ffffff 100%);color:#111;padding:24px}
    .card{max-width:520px;width:100%;text-align:center;background:#fff;border-radius:20px;padding:40px 32px;box-shadow:0 0 15px rgba(64,103,159,.35);animation:in .5s ease both}
    .code{font-size:88px;font-weight:800;line-height:1;background:radial-gradient(ellipse at center,#ABC9E9 0%,#40679F 100%);-webkit-background-clip:text;background-clip:text;color:transparent}
    h1{color:#40679F;font-size:22px;margin:12px 0 6px}
    p{font-size:14px;line-height:1.6;margin:0}
    .act{margin-top:24px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
    a,button{font:inherit;font-size:14px;font-weight:600;border-radius:8px;padding:10px 18px;text-decoration:none;cursor:pointer;transition:all .3s}
    .p{background:#40679F;color:#fff;border:2px solid #40679F}.p:hover{background:#7C9CBF;border-color:#7C9CBF;transform:translateY(-2px)}
    .o{background:#fff;color:#40679F;border:2px solid #40679F}.o:hover{background:#40679F;color:#fff;transform:translateY(-2px)}
    img{height:34px;margin-bottom:18px}
    @keyframes in{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
    @media (prefers-reduced-motion:reduce){.card{animation:none}a,button{transition:none}}
</style>
</head>
<body>
<main class="card">
    <img src="{{ asset('mivent_assets/logoColor.png') }}" alt="Mivent">
    <div class="code">@yield('code')</div>
    <h1>@yield('title')</h1>
    <p>@yield('message')</p>
    <div class="act">
        <button type="button" class="o" onclick="history.length > 1 ? history.back() : location.assign('{{ url('/') }}')">Kembali</button>
        <a class="p" href="{{ url('/') }}">Ke Beranda</a>
    </div>
</main>
</body>
</html>
