<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('codigo') · SISARST</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0b2d4d 0%, #0d5aa7 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
        }
        .card {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            backdrop-filter: blur(12px);
            border-radius: 1.25rem;
            padding: 3rem 3.5rem;
            text-align: center;
            max-width: 480px;
            width: 90%;
        }
        .codigo {
            font-size: 5rem; font-weight: 900; line-height: 1;
            letter-spacing: -.04em;
            color: rgba(255,255,255,.15);
            margin-bottom: .5rem;
        }
        .icono { font-size: 3rem; margin-bottom: 1rem; display: block; }
        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: .75rem; }
        p  { font-size: .93rem; color: rgba(255,255,255,.75); line-height: 1.6; margin-bottom: 1.75rem; }
        .btn {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #fff; color: #0d5aa7;
            border: none; border-radius: .65rem;
            padding: .65rem 1.5rem;
            font-size: .9rem; font-weight: 600;
            text-decoration: none; cursor: pointer;
            transition: opacity .15s;
        }
        .btn:hover { opacity: .88; }
        .brand {
            position: fixed; top: 1.5rem; left: 1.75rem;
            font-size: .85rem; font-weight: 700;
            color: rgba(255,255,255,.6);
            letter-spacing: .04em;
        }
    </style>
</head>
<body>
    <div class="brand">SISARST</div>
    <div class="card">
        <div class="codigo">@yield('codigo')</div>
        <span class="icono">@yield('icono')</span>
        <h1>@yield('titulo')</h1>
        <p>@yield('mensaje')</p>
        <a href="{{ url('/dashboard') }}" class="btn">← Volver al inicio</a>
    </div>
</body>
</html>
