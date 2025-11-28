<style>
/* ========================================
   PREMIUM FINANCE DASHBOARD THEME
   Glassmorphism & Futuristic Design
======================================== */

:root {
    /* --- Base Variables (Default Dark) --- */
    --bg-body: #0f172a;
    --bg-card: rgba(30, 41, 59, 0.7);
    --border-card: rgba(255, 255, 255, 0.08);
    --text-primary: #f8fafc;
    --text-secondary: #94a3b8;
    --bg-sidebar: rgba(15, 23, 42, 0.8);
    --sidebar-active: rgba(99, 102, 241, 0.15);
    --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    
    /* --- Brand Colors (Constant) --- */
    --color-primary: #6366f1;
    --color-secondary: #8b5cf6;
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-danger: #ef4444;
    --color-info: #3b82f6;
    
    /* --- Gradients --- */
    --grad-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    --grad-glow: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%);
}

[data-theme="light"] {
    /* --- Light Theme Overrides --- */
    --bg-body: #f0f2f5;
    --bg-card: rgba(255, 255, 255, 0.9);
    --border-card: rgba(0, 0, 0, 0.05);
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --bg-sidebar: rgba(255, 255, 255, 0.8);
    --sidebar-active: rgba(99, 102, 241, 0.1);
    --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    --grad-glow: radial-gradient(circle at top right, rgba(99, 102, 241, 0.05), transparent 40%);
}

/* Global Reset & Base */
body {
    background-color: var(--bg-body);
    color: var(--text-primary);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* --- Premium Card Component --- */
.premium-card {
    background: var(--bg-card);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--border-card);
    border-radius: 24px;
    padding: 24px;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    box-shadow: var(--shadow-card);
}

.premium-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: var(--grad-glow);
    pointer-events: none;
}

.premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: rgba(99, 102, 241, 0.3);
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
    box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
}

.wallet-overview::after {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    filter: blur(40px);
}

.wallet-balance-title {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.9;
    margin-bottom: 8px;
}

.wallet-balance-amount {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: -0.025em;
    margin-bottom: 4px;
}

.wallet-balance-sub {
    font-size: 1rem;
    opacity: 0.8;
}

/* --- Quick Actions --- */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 16px;
    margin-top: 24px;
}

.action-btn-premium {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
    transition: all 0.2s;
}

.action-btn-premium:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: white;
}

/* --- SVG Icons --- */
.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.icon-box svg {
    width: 24px;
    height: 24px;
    stroke-width: 2;
}

/* --- Stats Grid --- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
    margin-top: 24px;
}

.stat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-info h6 {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 4px;
    text-transform: uppercase;
    font-weight: 600;
}

.stat-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

/* Variant Colors (Backgrounds adapt to theme, but keep tint) */
.variant-purple .icon-box { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
.variant-green .icon-box { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.variant-orange .icon-box { background: rgba(249, 115, 22, 0.15); color: #f97316; }
.variant-blue .icon-box { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.variant-pink .icon-box { background: rgba(236, 72, 153, 0.15); color: #ec4899; }

/* --- Sidebar Styling --- */
.dashboard-sidebar {
    background: var(--bg-sidebar);
    backdrop-filter: blur(12px);
    border: 1px solid var(--border-card);
    border-radius: 24px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-card);
}

.dashboard-user {
    background: var(--grad-primary) !important;
    border-radius: 20px;
    padding: 24px;
    text-align: center;
    margin-bottom: 24px;
    color: white;
}

.user-thumb {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    margin: 0 auto 15px;
    border: 4px solid rgba(255,255,255,0.3);
    overflow: hidden;
}

.user-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-dashboard-tab li {
    list-style: none;
    margin-bottom: 8px;
}

.user-dashboard-tab li a {
    display: flex;
    align-items: center;
    padding: 14px 18px;
    border-radius: 12px;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    background: transparent;
}

.user-dashboard-tab li a:hover, 
.user-dashboard-tab li a.active {
    background: var(--sidebar-active);
    color: var(--color-primary);
    transform: translateX(5px);
}

.user-dashboard-tab li a i,
.user-dashboard-tab li a svg {
    margin-right: 12px;
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
}

/* --- Progress Bar --- */
.premium-progress {
    height: 8px;
    background: rgba(128, 128, 128, 0.2);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 12px;
}

.premium-progress-bar {
    height: 100%;
    border-radius: 4px;
    background: var(--color-primary);
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .wallet-balance-amount { font-size: 2rem; }
    .stats-grid { grid-template-columns: 1fr; }
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
}
</style>
