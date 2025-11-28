<style>
/* ========================================
   PREMIUM FINANCE DASHBOARD THEME
   Glassmorphism & Futuristic Design
======================================== */

:root {
    /* Premium Dark Theme Palette */
    --bg-dark: #0f172a;
    --card-bg-dark: rgba(30, 41, 59, 0.7);
    --card-border-dark: rgba(255, 255, 255, 0.08);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    
    /* Brand Colors */
    --primary: #6366f1;
    --secondary: #8b5cf6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    
    /* Gradients */
    --grad-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    --grad-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
    --grad-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    --grad-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    --grad-info: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    --grad-glow: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%);
}

body {
    background-color: var(--bg-dark);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Premium Card */
.premium-card {
    background: var(--card-bg-dark);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--card-border-dark);
    border-radius: 24px;
    padding: 24px;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.premium-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--grad-glow);
    pointer-events: none;
}

.premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: rgba(99, 102, 241, 0.3);
}

/* Main Wallet Card */
.wallet-overview {
    background: var(--grad-primary);
    border-radius: 24px;
    padding: 32px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
}

.wallet-overview::after {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
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

/* Quick Actions Grid */
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
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    color: white;
}

/* SVG Icons */
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
    color: var(--text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
    font-weight: 600;
}

.stat-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
}

/* Variant Colors */
.variant-purple .icon-box { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
.variant-green .icon-box { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.variant-orange .icon-box { background: rgba(249, 115, 22, 0.1); color: #f97316; }
.variant-blue .icon-box { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
.variant-pink .icon-box { background: rgba(236, 72, 153, 0.1); color: #ec4899; }

/* Progress Bar */
.premium-progress {
    height: 8px;
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 12px;
}

.premium-progress-bar {
    height: 100%;
    border-radius: 4px;
    background: var(--primary);
}

/* Responsive */
@media (max-width: 768px) {
    .wallet-balance-amount {
        font-size: 2rem;
    }
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
