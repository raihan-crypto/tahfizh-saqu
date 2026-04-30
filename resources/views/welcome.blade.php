<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SD Tahfizh SaQu UMA — Sistem Manajemen Tahfidz</title>
    <meta name="description" content="Sistem Manajemen Tahfidz SD Tahfizh SaQu UMA — Pantau hafalan, setoran, dan perkembangan santri secara digital.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f0f1a;
            color: #f1f5f9;
            overflow-x: hidden;
        }

        /* Animated gradient background */
        .bg-gradient {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1033 30%, #0f1f2e 60%, #0f0f1a 100%);
        }
        .bg-gradient::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(ellipse at 30% 20%, rgba(245,158,11,0.12) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 80%, rgba(139,92,246,0.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(16,185,129,0.06) 0%, transparent 60%);
            animation: bgFloat 25s ease-in-out infinite;
        }
        @keyframes bgFloat {
            0%, 100% { transform: translate(0,0) rotate(0deg); }
            33% { transform: translate(-2%,3%) rotate(1deg); }
            66% { transform: translate(3%,-2%) rotate(-1deg); }
        }

        /* Floating particles */
        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .particle {
            position: absolute; border-radius: 50%; opacity: 0.15;
            animation: floatParticle linear infinite;
        }
        @keyframes floatParticle {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 0.15; }
            90% { opacity: 0.15; }
            100% { transform: translateY(-20vh) scale(1); opacity: 0; }
        }

        .container { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; padding: 2rem; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; }

        /* Hero section */
        .hero { text-align: center; margin-bottom: 4rem; }
        .hero-icon {
            width: 5rem; height: 5rem; margin: 0 auto 1.5rem; border-radius: 1.5rem;
            background: linear-gradient(135deg, #f59e0b, #ef4444, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 40px rgba(245,158,11,0.3);
            animation: pulseIcon 3s ease-in-out infinite;
        }
        @keyframes pulseIcon {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 40px rgba(245,158,11,0.3); }
            50% { transform: scale(1.05); box-shadow: 0 15px 50px rgba(245,158,11,0.4); }
        }
        .hero h1 {
            font-size: 3rem; font-weight: 900; letter-spacing: -0.03em; line-height: 1.1; margin-bottom: 1rem;
            background: linear-gradient(135deg, #f59e0b, #fbbf24, #f59e0b);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero p { font-size: 1.125rem; color: #94a3b8; max-width: 600px; margin: 0 auto 2rem; line-height: 1.7; }

        /* Feature cards */
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; width: 100%; margin-bottom: 3rem; }
        .feature-card {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1.25rem; padding: 1.75rem; transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
            backdrop-filter: blur(10px); position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 3px 3px 0 0;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .feature-card:nth-child(1)::before { background: linear-gradient(90deg, #f59e0b, #f97316); }
        .feature-card:nth-child(2)::before { background: linear-gradient(90deg, #10b981, #06b6d4); }
        .feature-card:nth-child(3)::before { background: linear-gradient(90deg, #8b5cf6, #6366f1); }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(255,255,255,0.15); background: rgba(255,255,255,0.06); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 3rem; height: 3rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;
        }
        .feature-card h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.5rem; }
        .feature-card p { font-size: 0.875rem; color: #94a3b8; line-height: 1.6; }

        /* CTA button */
        .cta-btn {
            display: inline-flex; align-items: center; gap: 0.75rem;
            padding: 1rem 2.5rem; border-radius: 1rem; font-size: 1.125rem; font-weight: 700;
            background: linear-gradient(135deg, #f59e0b, #f97316); color: white; text-decoration: none;
            transition: all 0.3s ease; box-shadow: 0 8px 30px rgba(245,158,11,0.3);
            border: none; cursor: pointer;
        }
        .cta-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(245,158,11,0.45); }
        .cta-btn svg { width: 1.25rem; height: 1.25rem; }

        .footer { margin-top: 3rem; text-align: center; font-size: 0.8rem; color: #475569; }

        /* Animations */
        .fade-up { opacity: 0; transform: translateY(30px); animation: fadeUp 0.8s ease-out forwards; }
        .fade-up-1 { animation-delay: 0.1s; }
        .fade-up-2 { animation-delay: 0.2s; }
        .fade-up-3 { animation-delay: 0.3s; }
        .fade-up-4 { animation-delay: 0.4s; }
        .fade-up-5 { animation-delay: 0.5s; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .features { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <div class="particles">
        <div class="particle" style="width:6px;height:6px;background:#f59e0b;left:10%;animation-duration:18s;animation-delay:0s;"></div>
        <div class="particle" style="width:4px;height:4px;background:#8b5cf6;left:30%;animation-duration:22s;animation-delay:3s;"></div>
        <div class="particle" style="width:5px;height:5px;background:#10b981;left:50%;animation-duration:20s;animation-delay:6s;"></div>
        <div class="particle" style="width:3px;height:3px;background:#f59e0b;left:70%;animation-duration:24s;animation-delay:2s;"></div>
        <div class="particle" style="width:7px;height:7px;background:#6366f1;left:85%;animation-duration:19s;animation-delay:5s;"></div>
        <div class="particle" style="width:4px;height:4px;background:#ef4444;left:20%;animation-duration:21s;animation-delay:8s;"></div>
        <div class="particle" style="width:5px;height:5px;background:#06b6d4;left:60%;animation-duration:23s;animation-delay:1s;"></div>
    </div>

    <div class="container">
        <div class="hero">
            <div class="hero-icon fade-up fade-up-1">
                <svg style="width:2.5rem;height:2.5rem;color:white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <h1 class="fade-up fade-up-2">SD Tahfizh SaQu UMA</h1>
            <p class="fade-up fade-up-3">Sistem Manajemen Tahfidz terpadu untuk memantau hafalan, setoran harian, dan perkembangan santri secara digital.</p>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/admin') }}" class="cta-btn fade-up fade-up-4">
                        Masuk ke Dashboard
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="cta-btn fade-up fade-up-4">
                        Masuk ke Sistem
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                @endauth
            @endif
        </div>

        <div class="features">
            <div class="feature-card fade-up fade-up-3">
                <div class="feature-icon" style="background: linear-gradient(135deg, rgba(245,158,11,0.2), rgba(249,115,22,0.1));">
                    <svg style="width:1.5rem;height:1.5rem;color:#f59e0b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3>Pencatatan Setoran</h3>
                <p>Catat setoran hafalan harian santri meliputi ziyadah, sabqi, dan manzil secara real-time.</p>
            </div>
            <div class="feature-card fade-up fade-up-4">
                <div class="feature-icon" style="background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(6,182,212,0.1));">
                    <svg style="width:1.5rem;height:1.5rem;color:#10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3>Laporan & Statistik</h3>
                <p>Dashboard interaktif dengan visualisasi data pencapaian hafalan dan analisis perkembangan santri.</p>
            </div>
            <div class="feature-card fade-up fade-up-5">
                <div class="feature-icon" style="background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(99,102,241,0.1));">
                    <svg style="width:1.5rem;height:1.5rem;color:#8b5cf6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h3>Multi-Role Access</h3>
                <p>Akses berbeda untuk Admin, Ustadz, Guru, dan Wali Santri sesuai peran masing-masing.</p>
            </div>
        </div>

        <div class="footer fade-up fade-up-5">
            <p>© {{ date('Y') }} SD Tahfizh SaQu UMA — Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
        </div>
    </div>
</body>
</html>
