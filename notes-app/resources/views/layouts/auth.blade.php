<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Notes Hub')</title>
    <meta name="description" content="Smart Notes Hub – your intelligent note-taking workspace.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Light theme values */
            --bg-gradient: linear-gradient(135deg, #e0e7ff 0%, #f5f3ff 50%, #fdf2f8 100%);
            --card-bg: rgba(255, 255, 255, 0.75);
            --card-border: rgba(255, 255, 255, 0.45);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --input-bg: rgba(255, 255, 255, 0.8);
            --input-border: #e2e8f0;
            --shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
        }

        [data-theme="dark"] {
            /* Dark theme values */
            --bg-gradient: linear-gradient(135deg, #0b0f19 0%, #111827 50%, #1e1b4b 100%);
            --card-bg: rgba(17, 24, 39, 0.75);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --input-bg: rgba(15, 23, 42, 0.6);
            --input-border: #334155;
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            background: var(--bg-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            transition: var(--transition);
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient glowing background circles for premium look */
        .glow-circle {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.5;
            animation: floatGlow 12s infinite alternate ease-in-out;
        }
        .glow-circle-1 {
            background: #6366f1;
            top: 10%;
            left: 10%;
        }
        .glow-circle-2 {
            background: #d946ef;
            bottom: 10%;
            right: 10%;
            animation-delay: -6s;
        }

        @keyframes floatGlow {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(40px) scale(1.2); }
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 100;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            font-size: 1.25rem;
            cursor: pointer;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: var(--transition);
        }
        .theme-toggle-btn:hover {
            transform: scale(1.1) rotate(15deg);
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 2.5rem 2.25rem;
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            animation: fadeUp .5s cubic-bezier(0.16, 1, 0.3, 1);
            transition: var(--transition);
            color: var(--text-main);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo .logo-icon {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            color: #fff;
            margin-bottom: .85rem;
            box-shadow: 0 10px 25px rgba(99,102,241,.35);
            transition: var(--transition);
        }

        .auth-logo:hover .logo-icon {
            transform: scale(1.05) rotate(-5deg);
        }

        .auth-logo h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
        }

        .auth-logo p {
            font-size: .85rem;
            color: var(--text-muted);
            margin: .25rem 0 0 0;
        }

        .auth-title {
            font-size: 1.3rem;
            font-weight: 750;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            opacity: 0.9;
        }

        .form-input {
            width: 100%;
            padding: .75rem 1.1rem;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            font-size: .9rem;
            color: var(--text-main);
            font-family: inherit;
            outline: none;
            transition: var(--transition);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99,102,241,.18);
            background: var(--card-bg);
        }

        .form-input.is-invalid { border-color: #ef4444; }

        .invalid-feedback {
            font-size: .78rem;
            color: #ef4444;
            margin-top: .35rem;
            display: block;
        }

        .btn-auth {
            width: 100%;
            padding: .85rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 14px;
            color: #fff;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: .5rem;
            font-family: inherit;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99,102,241,.45);
        }

        .btn-auth:active { transform: translateY(0); }

        .auth-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: .85rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 650;
            text-decoration: none;
            transition: var(--transition);
        }
        .auth-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            pointer-events: none;
            opacity: 0.7;
        }

        .input-icon-wrap input {
            padding-left: 2.75rem;
        }

        /* Form flash messages inside auth page */
        .flash-alert {
            border-radius: 12px;
            padding: .85rem 1.1rem;
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            border: 1px solid transparent;
        }
        .alert-success {
            background-color: rgba(16, 185, 129, 0.12);
            border-color: rgba(16, 185, 129, 0.25);
            color: #047857;
        }
        [data-theme="dark"] .alert-success {
            color: #34d399;
        }
    </style>
</head>
<body>
    {{-- Glow circles for background visuals --}}
    <div class="glow-circle glow-circle-1"></div>
    <div class="glow-circle glow-circle-2"></div>

    {{-- Dark mode toggle --}}
    <button class="theme-toggle-btn" id="authThemeToggle" title="Toggle dark mode">
        <i class="bi bi-moon-stars-fill" id="authThemeIcon"></i>
    </button>

    <div class="auth-card">
        <div class="auth-logo">
            <a href="/" style="text-decoration: none; color: inherit;">
                <div class="logo-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                <h1>Smart Notes Hub</h1>
            </a>
            <p>Your intelligent note-taking workspace</p>
        </div>

        {{-- Session Success Alert --}}
        @if(session('success'))
            <div class="flash-alert alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const root = document.documentElement;
        const toggleBtn = document.getElementById('authThemeToggle');
        const toggleIcon = document.getElementById('authThemeIcon');

        function setTheme(theme) {
            root.setAttribute('data-theme', theme);
            localStorage.setItem('snhTheme', theme);
            toggleIcon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }

        // Initialize theme
        const currentTheme = localStorage.getItem('snhTheme') || 'light';
        setTheme(currentTheme);

        toggleBtn.addEventListener('click', () => {
            const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            setTheme(nextTheme);
        });
    </script>
</body>
</html>
