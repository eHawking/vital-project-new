<style>
/* ========================================
   PREMIUM LUXURY DASHBOARD THEME
   Glassmorphism & Dynamic Styling
======================================== */

:root {
    /* --- Base Variables (Default Dark) --- */
    --bg-body: #0f172a;
    --bg-card: rgba(30, 41, 59, 0.6);
    --border-card: rgba(255, 255, 255, 0.08);
    --text-primary: #f8fafc;
    --text-secondary: #94a3b8;
    --bg-sidebar: rgba(15, 23, 42, 0.85);
    --sidebar-active: rgba(255, 255, 255, 0.1);
    --shadow-card: 0 8px 32px 0 rgba(0, 0, 0, 0.36);
    
    /* --- Brand Colors (Dynamic) --- */
    --color-primary: #6366f1;
    --color-secondary: #8b5cf6;
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-danger: #ef4444;
    --color-info: #3b82f6;
    
    /* --- Gradients (Dynamic) --- */
    --grad-primary: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    --grad-glow: radial-gradient(circle at top right, rgba(255, 255, 255, 0.1), transparent 50%);
    --grad-glass: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
}

[data-theme="light"] {
    /* --- Light Theme Overrides --- */
    --bg-body: #f0f2f5;
    --bg-card: rgba(255, 255, 255, 0.75);
    --border-card: rgba(255, 255, 255, 0.4);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --bg-sidebar: rgba(255, 255, 255, 0.85);
    --sidebar-active: rgba(0, 0, 0, 0.05);
    --shadow-card: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    --grad-glow: radial-gradient(circle at top right, rgba(0, 0, 0, 0.05), transparent 50%);
    --grad-glass: linear-gradient(145deg, rgba(255, 255, 255, 0.6) 0%, rgba(255, 255, 255, 0.3) 100%);
}

/* Global Reset & Base */
body {
    background-color: var(--bg-body);
    color: var(--text-primary);
    font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* --- Premium Card Component --- */
.premium-card {
    background: var(--bg-card);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--border-card);
    border-radius: 24px;
    padding: 24px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-card);
}

.premium-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: var(--grad-glow);
    pointer-events: none;
    z-index: 0;
}

.premium-card:hover {
    transform: translateY(-5px) scale(1.01);
    box-shadow: 0 15px 40px -5px rgba(0, 0, 0, 0.2);
    border-color: var(--color-primary);
}

/* --- Wallet Overview Header --- */
.wallet-overview {
    background: var(--grad-primary);
    border-radius: 24px;
    padding: 32px;
    color: white; /* Always white text on primary gradient */
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255,255,255,0.1);
}

.wallet-overview::after {
    content: '';
    position: absolute;
    top: -50%; right: -20%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    filter: blur(40px);
    pointer-events: none;
}

.wallet-balance-title {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.9;
    margin-bottom: 10px;
    font-weight: 600;
}

.wallet-balance-amount {
    font-size: 3rem;
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 5px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.wallet-balance-sub {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 500;
}

/* --- Quick Actions --- */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.action-btn-premium {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    overflow: hidden;
}

.action-btn-premium:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-5px);
    color: white;
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.action-btn-premium span {
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

/* --- SVG Icons --- */
.icon-box {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    transition: transform 0.3s ease;
}

.stat-item:hover .icon-box {
    transform: rotate(10deg) scale(1.1);
}

.icon-box svg {
    width: 28px;
    height: 28px;
    stroke-width: 1.8;
}

/* --- Stats Grid --- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-top: 30px;
}

.stat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 28px;
}

.stat-info h6 {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 8px;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.stat-info h3 {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0;
    letter-spacing: -0.5px;
}

/* Variant Colors (Backgrounds adapt to theme, but keep tint) */
/* Using CSS variables allows these to be influenced by theme choice if desired, 
   but keeping distinct colors for semantic meaning is usually better */
.variant-purple .icon-box { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2); }
.variant-green .icon-box { background: rgba(16, 185, 129, 0.15); color: #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
.variant-orange .icon-box { background: rgba(249, 115, 22, 0.15); color: #f97316; box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2); }
.variant-blue .icon-box { background: rgba(59, 130, 246, 0.15); color: #3b82f6; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); }
.variant-pink .icon-box { background: rgba(236, 72, 153, 0.15); color: #ec4899; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2); }

/* --- Sidebar Styling (Full Width Menu Concept) --- */
.dashboard-sidebar {
    background: var(--bg-sidebar);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--border-card);
    border-radius: 24px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-card);
    height: 100%; /* Match height */
}

.dashboard-user {
    background: var(--grad-primary) !important;
    border-radius: 24px;
    padding: 30px;
    text-align: center;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.dashboard-user::before {
    content: '';
    position: absolute;
    top: -50px; left: -50px;
    width: 150px; height: 150px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    filter: blur(30px);
}

.user-thumb {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: 0 auto 20px;
    border: 4px solid rgba(255,255,255,0.3);
    overflow: hidden;
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
}

.user-thumb:hover {
    transform: scale(1.05);
    border-color: white;
}

.user-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-dashboard-tab li {
    list-style: none;
    margin-bottom: 10px;
}

.user-dashboard-tab li a {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border-radius: 16px;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    background: transparent;
    border: 1px solid transparent;
}

.user-dashboard-tab li a:hover {
    background: var(--sidebar-active);
    color: var(--text-primary);
    padding-left: 25px; /* Slide effect */
}

.user-dashboard-tab li a.active {
    background: var(--grad-primary);
    color: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.user-dashboard-tab li a i,
.user-dashboard-tab li a svg {
    margin-right: 15px;
    font-size: 1.3rem;
    width: 24px;
    text-align: center;
}

/* --- Progress Bar --- */
.premium-progress {
    height: 10px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    margin-top: 15px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.premium-progress-bar {
    height: 100%;
    border-radius: 10px;
    background: var(--grad-primary);
    position: relative;
}

.premium-progress-bar::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transform: translateX(-100%);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    100% { transform: translateX(100%); }
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .wallet-balance-amount { font-size: 2.5rem; }
    .stats-grid { grid-template-columns: 1fr; }
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
    
    /* Mobile Full Width Logic */
    .container-fluid { padding-left: 15px !important; padding-right: 15px !important; }
    .premium-card { border-radius: 20px; padding: 20px; }
    
    /* Hide Sidebar on Mobile (Since we have bottom nav) */
    .dashboard-sidebar { display: none; }
}
</style>
