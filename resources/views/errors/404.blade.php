<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página não encontrada | SISNA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sisna-blue: #365fcf;
            --sisna-blue-dark: #2148bc;
            --bg: #e9ecef;
            --text: #495057;
            --muted: #6c757d;
            --border: #ced4da;
        }

        * { box-sizing: border-box; }

        html, body { height: 100%; margin: 0; }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: var(--bg);
            color: var(--text);
            font-family: "Source Sans 3", "Source Sans Pro", -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            line-height: 1.5;
        }

        .card {
            width: 100%;
            max-width: 450px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .card-header {
            padding: 14px 20px;
            background: var(--sisna-blue);
            color: #fff;
            text-align: center;
            font-size: 1.15rem;
        }

        .card-body {
            padding: 32px 25px 28px;
            text-align: center;
        }

        .code {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin: 0 0 20px;
            color: var(--sisna-blue);
            font-size: 6.5rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -2px;
        }

        .code svg {
            width: .86em;
            height: .86em;
            flex: none;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
        }

        p {
            margin: 0 auto 26px;
            max-width: 34ch;
            color: var(--muted);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border: 1px solid var(--sisna-blue);
            border-radius: 4px;
            background: var(--sisna-blue);
            color: #fff;
            font: inherit;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color .15s, border-color .15s;
        }

        .btn:hover { background: var(--sisna-blue-dark); border-color: var(--sisna-blue-dark); }

        .btn-outline {
            background: #fff;
            color: var(--sisna-blue);
        }

        .btn-outline:hover { background: #f1f4fb; color: var(--sisna-blue-dark); }

        .btn:focus-visible {
            outline: 3px solid rgba(60, 90, 176, .4);
            outline-offset: 2px;
        }

        .btn svg { width: 16px; height: 16px; flex: none; }

        @media (max-width: 420px) {
            .code { font-size: 5rem; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="card-header">Algo saiu do caminho</div>

        <div class="card-body">
            <div class="code" role="img" aria-label="Erro 404">
                <span aria-hidden="true">4</span>

                <svg viewBox="0 0 120 120" aria-hidden="true">
                    <circle cx="60" cy="60" r="59" fill="#3c5ab0"/>
                    <circle cx="60" cy="60" r="54" fill="none" stroke="#fff" stroke-opacity=".55" stroke-width="1.5"/>
                    <!-- passarinho -->
                    <path fill="#fff" d="M30 80 L47 70 C40 62 44 48 56 44 C63 41 71 43 75 48 L88 46 L78 54 C79 66 72 76 60 78 L40 86 Z"/>
                    <circle cx="68" cy="50" r="2.4" fill="#3c5ab0"/>
                    <path d="M58 78 L58 90 M64 77 L66 90" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>
                </svg>

                <span aria-hidden="true">4</span>
            </div>

            <h1>Página não encontrada</h1>
            <p>O endereço que você acessou não existe ou foi movido. Confira se digitou corretamente ou volte para o início.</p>

            <div class="actions">
                <a class="btn" href="{{ url('/') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>
                    Ir para o início
                </a>
                <button type="button" class="btn btn-outline" onclick="history.length > 1 ? history.back() : (window.location = '{{ url('/') }}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                    Voltar
                </button>
            </div>
        </div>
    </main>
</body>
</html>