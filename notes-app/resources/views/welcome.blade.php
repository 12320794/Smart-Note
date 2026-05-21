@extends('layouts.app')

@section('content')
<div class="auth-bg-shapes">
    <div class="shape-1"></div>
    <div class="shape-2"></div>
    <div class="shape-3"></div>
</div>

<div class="landing-container">
    <nav class="navbar glass-panel">
        <h1 class="logo">Anti<span>Notes</span></h1>
        <div class="nav-links">
            @auth
                <a href="{{ route('notes.index') }}" class="btn text-btn">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn primary-btn" style="padding: 0.5rem 1rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn text-btn">Log In</a>
                <a href="{{ route('register') }}" class="btn primary-btn" style="padding: 0.5rem 1rem;">Get Started</a>
            @endauth
        </div>
    </nav>

    <main class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Your Thoughts, <span>Amplified.</span></h1>
            <p class="hero-subtitle">Experience a beautiful, frictionless note-taking environment designed to help you capture ideas instantly. Rich formatting, instant search, and intelligent tagging.</p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('notes.index') }}" class="btn primary-btn hero-btn">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn primary-btn hero-btn">Start for free</a>
                    <a href="{{ route('login') }}" class="btn glass-btn hero-btn">Log In</a>
                @endauth
            </div>
        </div>
        
        <div class="hero-visual glass-card">
            <div class="visual-header">
                <div class="dots"><span></span><span></span><span></span></div>
                <div class="visual-title">AntiNotes Dashboard</div>
            </div>
            <div class="visual-body">
                <div class="visual-sidebar">
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line short"></div>
                    <div class="skeleton-box"></div>
                    <div class="skeleton-box"></div>
                </div>
                <div class="visual-main">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-content"></div>
                    <div class="skeleton-content w-80"></div>
                    <div class="skeleton-content w-60"></div>
                </div>
            </div>
        </div>
    </main>

    <section class="features-section">
        <h2 class="section-title">Why choose AntiNotes?</h2>
        <div class="features-grid">
            <div class="feature-card glass-card">
                <div class="feature-icon">✨</div>
                <h3>Beautiful Design</h3>
                <p>A stunning, distraction-free interface utilizing glassmorphism and modern aesthetics.</p>
            </div>
            <div class="feature-card glass-card">
                <div class="feature-icon">📝</div>
                <h3>Rich Text Editor</h3>
                <p>Format your notes easily with a powerful WYSIWYG editor. Bold, italic, lists, and more.</p>
            </div>
            <div class="feature-card glass-card">
                <div class="feature-icon">🏷️</div>
                <h3>Smart Tagging</h3>
                <p>Organize your thoughts with tags. Filter and find what you need in seconds.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} AntiNotes. Crafted with <span style="color: var(--danger);">&hearts;</span></p>
    </footer>
</div>
@endsection
