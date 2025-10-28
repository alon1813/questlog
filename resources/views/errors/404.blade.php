<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Ruta No Encontrada</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Fira+Code&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #1a1a2e;
            --text-primary: #e94560;
            --border-color: #533483;
            --success-color: #32a852;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-primary); color: #dcdcdc; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; text-align: center; font-family: 'Fira Code', monospace; }
        .terminal-window { background-color: #0d0d0d; border: 1px solid var(--border-color); border-radius: 8px; padding: 30px; max-width: 600px; width: 90%; }
        .terminal-header { text-align: left; margin-bottom: 20px; color: #888; }
        .terminal-body p { margin-bottom: 15px; text-align: left; }
        .terminal-body .prompt { color: var(--success-color); }
        .cursor { display: inline-block; background-color: #fff; width: 10px; height: 1.2em; animation: blink 1s step-end infinite; }
        @keyframes blink { 50% { background-color: transparent; } }
        a.cta-button { display: inline-block; background-color: var(--text-primary); color: #fff; padding: 15px 30px; border-radius: 8px; font-weight: 700; text-decoration: none; text-transform: uppercase; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="terminal-window">
        <div class="terminal-header">QuestLog OS [Version 1.0]</div>
        <div class="terminal-body">
            <p><span class="prompt">></span> ERROR 404: RUTA NO ENCONCONTRADA.</p>
            <p>Parece que has tomado un camino equivocado en el código. No te preocupes, no hay game over.</p>
            <p>Usa el portal de abajo para volver a una zona segura.<span class="cursor"></span></p>
        </div>
    </div>
    <a href="{{ route('dashboard') }}" class="cta-button">Volver al Dashboard</a>
</body>
</html>