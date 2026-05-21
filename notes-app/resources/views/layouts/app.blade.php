<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Notes Hub') – Smart Notes Hub</title>
    <meta name="description" content="Smart Notes Hub – your intelligent note-taking workspace.">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           CSS CUSTOM PROPERTIES (Design Tokens)
        ═══════════════════════════════════════════ */
        :root {
            --primary:        #6366f1;
            --primary-dark:   #4f46e5;
            --primary-light:  #e0e7ff;
            --secondary:      #8b5cf6;
            --success:        #10b981;
            --warning:        #f59e0b;
            --danger:         #ef4444;
            --info:           #3b82f6;

            --bg:             #f8fafc;
            --surface:        #ffffff;
            --surface-2:      #f1f5f9;
            --border:         #e2e8f0;
            --text:           #1e293b;
            --text-muted:     #64748b;
            --sidebar-w:      260px;
            --sidebar-bg:     #1e1b4b;
            --sidebar-text:   #c7d2fe;
            --sidebar-active: #6366f1;
            --radius:         14px;
            --shadow:         0 4px 24px rgba(99,102,241,.10);
            --shadow-card:    0 2px 12px rgba(0,0,0,.07);
            --transition:     .22s cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --bg:         #0f172a;
            --surface:    #1e293b;
            --surface-2:  #273045;
            --border:     #334155;
            --text:       #f1f5f9;
            --text-muted: #94a3b8;
            --shadow:     0 4px 24px rgba(0,0,0,.4);
            --shadow-card:0 2px 12px rgba(0,0,0,.3);
        }

        /* ═══════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            transition: background var(--transition), color var(--transition);
        }

        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-dark); }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform var(--transition);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand .brand-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0; }

        .nav-section-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            padding: .75rem 1.25rem .35rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1.25rem;
            color: var(--sidebar-text);
            font-size: .875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all var(--transition);
            cursor: pointer;
        }

        .sidebar-link:hover {
            color: #fff;
            background: rgba(255,255,255,.06);
            border-left-color: rgba(255,255,255,.2);
        }

        .sidebar-link.active {
            color: #fff;
            background: rgba(99,102,241,.25);
            border-left-color: var(--primary);
        }

        .sidebar-link i { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: .65rem;
            padding: .1rem .45rem;
            border-radius: 99px;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: .85rem;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════
           MAIN CONTENT AREA
        ═══════════════════════════════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════════
           TOP NAV
        ═══════════════════════════════════════════ */
        .topnav {
            position: sticky; top: 0; z-index: 900;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            backdrop-filter: blur(8px);
            transition: background var(--transition);
        }

        .topnav .search-wrap {
            flex: 1;
            max-width: 520px;
            position: relative;
        }

        .topnav .search-wrap input {
            width: 100%;
            padding: .5rem 1rem .5rem 2.6rem;
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 99px;
            font-size: .875rem;
            color: var(--text);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .topnav .search-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }

        .topnav .search-wrap .search-icon {
            position: absolute;
            left: .85rem; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: .9rem;
            pointer-events: none;
        }

        .topnav-actions { display: flex; align-items: center; gap: .5rem; margin-left: auto; }

        .icon-btn {
            width: 38px; height: 38px;
            border: none;
            border-radius: 10px;
            background: var(--surface-2);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            font-size: 1rem;
        }

        .icon-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ═══════════════════════════════════════════
           PAGE CONTENT
        ═══════════════════════════════════════════ */
        .page-content { padding: 1.75rem 1.5rem; flex: 1; }

        /* ═══════════════════════════════════════════
           CARDS
        ═══════════════════════════════════════════ */
        .card-surface {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            transition: box-shadow var(--transition), transform var(--transition);
        }

        .card-surface:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        /* ═══════════════════════════════════════════
           NOTE CARDS (sticky-note style)
        ═══════════════════════════════════════════ */
        .note-card {
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: box-shadow var(--transition), transform var(--transition);
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 200px;
        }

        .note-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,.13);
        }

        .note-card .note-pin {
            position: absolute;
            top: .6rem; right: .6rem;
            color: var(--warning);
            font-size: 1rem;
        }

        .note-card .note-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .4rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .note-card .note-preview {
            font-size: .78rem;
            color: var(--text-muted);
            line-height: 1.6;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .note-card .note-meta {
            margin-top: .75rem;
            display: flex;
            align-items: center;
            gap: .4rem;
            flex-wrap: wrap;
        }

        .priority-badge {
            font-size: .65rem;
            font-weight: 700;
            padding: .1rem .5rem;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .priority-high   { background: #fee2e2; color: #b91c1c; }
        .priority-medium { background: #fef3c7; color: #92400e; }
        .priority-low    { background: #d1fae5; color: #065f46; }

        [data-theme="dark"] .priority-high   { background: rgba(239,68,68,.2);   color: #fca5a5; }
        [data-theme="dark"] .priority-medium { background: rgba(245,158,11,.2);  color: #fcd34d; }
        [data-theme="dark"] .priority-low    { background: rgba(16,185,129,.2);  color: #6ee7b7; }

        .tag-badge {
            font-size: .62rem;
            font-weight: 600;
            padding: .1rem .45rem;
            border-radius: 99px;
            color: #fff;
        }

        .note-date {
            font-size: .65rem;
            color: var(--text-muted);
            margin-left: auto;
        }

        /* ═══════════════════════════════════════════
           STAT CARDS
        ═══════════════════════════════════════════ */
        .stat-card {
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
            transition: all var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-card .stat-val {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
            color: var(--text);
        }

        .stat-card .stat-lbl {
            font-size: .78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════════ */
        .btn-primary-custom {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: .6rem 1.4rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,.35);
            color: #fff;
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
            padding: .55rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: .875rem;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: #fff;
        }

        /* ═══════════════════════════════════════════
           FORMS
        ═══════════════════════════════════════════ */
        .form-control-custom {
            width: 100%;
            padding: .6rem 1rem;
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: .875rem;
            color: var(--text);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
            font-family: inherit;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
            background: var(--surface);
        }

        .form-label-custom {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: .35rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        /* ═══════════════════════════════════════════
           ALERTS / FLASH MESSAGES
        ═══════════════════════════════════════════ */
        .flash-alert {
            border-radius: 12px;
            padding: .85rem 1.25rem;
            font-size: .875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .6rem;
            animation: slideDown .3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════════
           PAGE HEADER
        ═══════════════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* ═══════════════════════════════════════════
           MOBILE SIDEBAR TOGGLE
        ═══════════════════════════════════════════ */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text);
            cursor: pointer;
        }

        /* ═══════════════════════════════════════════
           RICH TEXT EDITOR AREA
        ═══════════════════════════════════════════ */
        .ck-editor__editable {
            min-height: 320px !important;
            background: var(--surface-2) !important;
            color: var(--text) !important;
            border-radius: 0 0 10px 10px !important;
        }

        .ck.ck-toolbar {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            border-radius: 10px 10px 0 0 !important;
        }

        .ck.ck-editor__main > .ck-editor__editable {
            border-color: var(--border) !important;
        }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,.4);
            }

            .main-wrap {
                margin-left: 0;
            }

            .sidebar-toggle { display: flex; }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.5);
                z-index: 999;
            }

            .sidebar-overlay.open { display: block; }
        }

        /* ═══════════════════════════════════════════
           MISC UTILITIES
        ═══════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--text-muted);
        }

        .empty-state .empty-icon {
            font-size: 4rem;
            opacity: .3;
            margin-bottom: 1rem;
        }

        .folder-card {
            border-radius: var(--radius);
            padding: 1.25rem;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
            cursor: pointer;
            transition: all var(--transition);
        }

        .folder-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .folder-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #fff;
            flex-shrink: 0;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar overlay for mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ─────────────── SIDEBAR ─────────────── --}}
<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-name">
            <div class="brand-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
            Smart Notes Hub
        </div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.35);margin-top:.35rem;">Your intelligent workspace</div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main Menu</div>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <a href="{{ route('notes.index') }}" class="sidebar-link {{ request()->routeIs('notes.*') ? 'active' : '' }}">
            <i class="bi bi-sticky-fill"></i> All Notes
        </a>

        <a href="{{ route('notes.create') }}" class="sidebar-link">
            <i class="bi bi-plus-circle-fill"></i> New Note
        </a>

        <div class="nav-section-label" style="margin-top:.5rem;">Organize</div>

        <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i> Folders
        </a>

        <a href="{{ route('tags.index') }}" class="sidebar-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> Tags
        </a>

        <div class="nav-section-label" style="margin-top:.5rem;">Filters</div>

        <a href="{{ route('notes.index', ['priority' => 'high']) }}" class="sidebar-link">
            <i class="bi bi-exclamation-circle-fill" style="color:#ef4444;"></i> High Priority
        </a>

        <a href="{{ route('notes.index', ['priority' => 'medium']) }}" class="sidebar-link">
            <i class="bi bi-dash-circle-fill" style="color:#f59e0b;"></i> Medium Priority
        </a>

        <a href="{{ route('notes.index', ['priority' => 'low']) }}" class="sidebar-link">
            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Low Priority
        </a>

        <div class="nav-section-label" style="margin-top:.5rem;">System</div>

        <a href="{{ route('trash.index') }}" class="sidebar-link {{ request()->routeIs('trash.*') ? 'active' : '' }}">
            <i class="bi bi-trash3-fill"></i> Trash
        </a>

        <a href="{{ route('profile.index') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profile & Settings
        </a>
    </nav>

    {{-- User footer --}}
    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:.75rem;">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="user-avatar" style="object-fit:cover; border-radius:50%; border: 1.5px solid rgba(255,255,255,0.2);" alt="Avatar">
            @else
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            @endif
            <div>
                <div style="font-size:.82rem;font-weight:600;color:#fff;">{{ Auth::user()->name }}</div>
                <div style="font-size:.7rem;color:rgba(255,255,255,.4);">{{ Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:auto;">
                @csrf
                <button type="submit" class="icon-btn" title="Logout" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ─────────────── MAIN WRAP ─────────────── --}}
<div class="main-wrap">

    {{-- Top Navigation --}}
    <header class="topnav">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

        {{-- Global search --}}
        <form method="GET" action="{{ route('notes.index') }}" class="search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input type="search" name="search" placeholder="Search notes, tags, folders…"
                   value="{{ request('search') }}" autocomplete="off">
        </form>

        {{-- Actions --}}
        <div class="topnav-actions">
            {{-- Dark mode toggle --}}
            <button class="icon-btn" id="themeToggle" title="Toggle dark mode">
                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
            </button>

            {{-- New Note shortcut --}}
            <a href="{{ route('notes.create') }}" class="btn-primary-custom" style="text-decoration:none;">
                <i class="bi bi-plus-lg"></i> New Note
            </a>
        </div>
    </header>

    {{-- Flash messages --}}
    <div style="padding:.75rem 1.5rem 0;">
        @if(session('success'))
            <div class="flash-alert alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-alert alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="flash-alert alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="page-content">
        @yield('content')
    </main>

</div>{{-- /.main-wrap --}}

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ═════════════════════════════════
   DARK / LIGHT MODE
═════════════════════════════════ */
const html       = document.documentElement;
const themeBtn   = document.getElementById('themeToggle');
const themeIcon  = document.getElementById('themeIcon');

function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('snhTheme', theme);
    themeIcon.className = theme === 'dark'
        ? 'bi bi-sun-fill'
        : 'bi bi-moon-stars-fill';
}

// Load saved theme
const savedTheme = localStorage.getItem('snhTheme') || 'light';
applyTheme(savedTheme);

themeBtn.addEventListener('click', () => {
    applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
});

/* ═════════════════════════════════
   MOBILE SIDEBAR
═════════════════════════════════ */
const sidebar  = document.getElementById('sidebar');
const overlay  = document.getElementById('sidebarOverlay');

function toggleSidebar() {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}

function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
}

/* Auto-close flash after 4s */
setTimeout(() => {
    document.querySelectorAll('.flash-alert').forEach(el => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        if (bsAlert) bsAlert.close();
    });
}, 4000);
</script>

@stack('scripts')
</body>
</html>
