<style>
/* ========================================
   MODERN FINANCE DASHBOARD THEME
   Futuristic Design with Light/Dark Mode
======================================== */

:root, [data-theme="dark"] {
    /* Primary Colors - Dark Theme */
    --primary-dark: #0a1f3d;
    --secondary-dark: #1a2f4d;
    --accent-blue: #4c6fff;
    --accent-purple: #7c3aed;
    --accent-green: #10b981;
    --accent-yellow: #fbbf24;
    --accent-orange: #f97316;
    --accent-pink: #ec4899;
    --accent-cyan: #06b6d4;
    
    /* Gradient Combinations */
    --gradient-purple-blue: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-green-blue: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --gradient-orange-red: linear-gradient(135deg, #f953c6 0%, #b91d73 100%);
    --gradient-yellow-orange: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
    --gradient-blue-cyan: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --gradient-pink-purple: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --gradient-teal-green: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --gradient-violet-blue: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    
    /* Text Colors - Dark Theme */
    --text-white: #ffffff;
    --text-gray: #94a3b8;
    --text-light: #cbd5e1;
    --text-dark: #1e293b;
    
    /* Card & Background - Dark Theme */
    --card-bg: rgba(26, 47, 77, 0.6);
    --card-hover: rgba(26, 47, 77, 0.9);
    --glass-bg: rgba(255, 255, 255, 0.05);
    --border-color: rgba(255, 255, 255, 0.1);
    
    /* Shadows - Dark Theme */
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.3);
    --shadow-glow: 0 0 20px rgba(76, 111, 255, 0.4);
    
    /* Border Radius */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
}

/* ========================================
   LIGHT THEME VARIABLES
======================================== */
[data-theme="light"] {
    /* Primary Colors - Light Theme */
    --primary-dark: #f8fafc;
    --secondary-dark: #f1f5f9;
    --accent-blue: #3b82f6;
    --accent-purple: #8b5cf6;
    --accent-green: #10b981;
    --accent-yellow: #f59e0b;
    --accent-orange: #f97316;
    --accent-pink: #ec4899;
    --accent-cyan: #06b6d4;
    
    /* Gradients remain vibrant in light theme */
    --gradient-purple-blue: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-green-blue: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --gradient-orange-red: linear-gradient(135deg, #f953c6 0%, #b91d73 100%);
    --gradient-yellow-orange: linear-gradient(135deg, #fad961 0%, #f76b1c 100%);
    --gradient-blue-cyan: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --gradient-pink-purple: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --gradient-teal-green: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --gradient-violet-blue: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    
    /* Text Colors - Light Theme */
    --text-white: #1e293b;
    --text-gray: #64748b;
    --text-light: #475569;
    --text-dark: #0f172a;
    
    /* Card & Background - Light Theme */
    --card-bg: rgba(255, 255, 255, 0.9);
    --card-hover: rgba(255, 255, 255, 1);
    --glass-bg: rgba(255, 255, 255, 0.7);
    --border-color: rgba(0, 0, 0, 0.08);
    
    /* Shadows - Light Theme */
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.15);
    --shadow-glow: 0 0 20px rgba(59, 130, 246, 0.3);
}

/* ========================================
   GLOBAL STYLES
======================================== */
* {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

body {
    background: var(--primary-dark);
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text-white);
    overflow-x: hidden;
    transition: background-color 0.3s ease;
}

.user-dashboard {
    background: var(--primary-dark);
    min-height: 100vh;
    padding: 20px 0;
    transition: background-color 0.3s ease;
}

/* ========================================
   DASHBOARD CARDS - MODERN STYLE
======================================== */
.dashboard-item {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.dashboard-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-purple-blue);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.dashboard-item:hover::before {
    opacity: 1;
}

.dashboard-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg), var(--shadow-glow);
    background: var(--card-hover);
    border-color: var(--border-color);
}

/* Gradient Card Variants */
.dashboard-item.gradient-card-1 {
    background: var(--gradient-purple-blue);
    border: none;
}

.dashboard-item.gradient-card-2 {
    background: var(--gradient-green-blue);
    border: none;
}

.dashboard-item.gradient-card-3 {
    background: var(--gradient-yellow-orange);
    border: none;
}

.dashboard-item.gradient-card-4 {
    background: var(--gradient-blue-cyan);
    border: none;
}

.dashboard-item.gradient-card-5 {
    background: var(--gradient-pink-purple);
    border: none;
}

.dashboard-item.gradient-card-6 {
    background: var(--gradient-teal-green);
    border: none;
}

/* Dashboard Item Header */
.dashboard-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left .title {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.header-left .ammount {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-white);
    margin: 0;
    line-height: 1.2;
}

/* Icon Styling */
.dashboard-item .icon, 
.dashboard-item .right-content .icon {
    width: 56px;
    height: 56px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--text-white);
    transition: all 0.3s ease;
}

.dashboard-item:hover .icon {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1) rotate(5deg);
}

/* ========================================
   WALLET CARD - TOP SECTION
======================================== */
.wallet-card {
    background: var(--gradient-purple-blue);
    border-radius: var(--radius-xl);
    padding: 30px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.wallet-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.wallet-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.wallet-title {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.wallet-balance {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-white);
    margin: 10px 0;
}

.wallet-sub-balance {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
}

/* ========================================
   ACTION BUTTONS
======================================== */
.action-buttons {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin: 24px 0;
}

.action-btn {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: var(--text-white);
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    color: var(--text-white);
}

.action-btn i {
    font-size: 24px;
    color: var(--accent-blue);
}

.action-btn span {
    font-size: 12px;
    font-weight: 500;
}

/* ========================================
   NAVIGATION GRID
======================================== */
.nav-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin: 24px 0;
}

.nav-item {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: var(--text-white);
}

.nav-item:hover {
    background: var(--card-hover);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    color: var(--text-white);
}

.nav-item i {
    font-size: 28px;
    color: var(--accent-cyan);
}

.nav-item span {
    font-size: 13px;
    font-weight: 500;
    text-align: center;
}

/* ========================================
   STATISTICS SECTION
======================================== */
.stats-section {
    margin: 24px 0;
}

.stats-header {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-white);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stat-card {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    background: var(--card-hover);
    transform: translateX(5px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 12px;
    color: var(--text-gray);
    margin-bottom: 4px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-white);
}

/* Stat Card Color Variants */
.stat-card.variant-1 .stat-icon { background: var(--gradient-green-blue); }
.stat-card.variant-2 .stat-icon { background: var(--gradient-yellow-orange); }
.stat-card.variant-3 .stat-icon { background: var(--gradient-pink-purple); }
.stat-card.variant-4 .stat-icon { background: var(--gradient-blue-cyan); }
.stat-card.variant-5 .stat-icon { background: var(--gradient-teal-green); }
.stat-card.variant-6 .stat-icon { background: var(--gradient-orange-red); }

/* ========================================
   LOAD MORE BUTTON
======================================== */
.load-more-btn {
    background: linear-gradient(135deg, #f953c6 0%, #b91d73 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 32px;
    color: var(--text-white);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(249, 83, 198, 0.4);
}

.load-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(249, 83, 198, 0.6);
}

/* ========================================
   TRANSACTIONS TABLE
======================================== */
.transactions-section {
    margin: 24px 0;
}

.transaction-item {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.transaction-item:hover {
    background: var(--card-hover);
    border-color: var(--border-color);
}

.transaction-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.transaction-icon {
    width: 44px;
    height: 44px;
    background: var(--gradient-purple-blue);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: var(--text-white);
}

.transaction-details h6 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-white);
    margin: 0 0 4px 0;
}

.transaction-details p {
    font-size: 12px;
    color: var(--text-gray);
    margin: 0;
}

.transaction-amount {
    font-size: 16px;
    font-weight: 700;
    color: var(--accent-green);
}

.transaction-amount.negative {
    color: var(--accent-pink);
}

/* ========================================
   ANIMATIONS
======================================== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(76, 111, 255, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(76, 111, 255, 0.8);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Apply Animations */
.dashboard-item {
    animation: fadeInUp 0.5s ease-out;
}

.dashboard-item:nth-child(1) { animation-delay: 0.1s; }
.dashboard-item:nth-child(2) { animation-delay: 0.2s; }
.dashboard-item:nth-child(3) { animation-delay: 0.3s; }
.dashboard-item:nth-child(4) { animation-delay: 0.4s; }
.dashboard-item:nth-child(5) { animation-delay: 0.5s; }
.dashboard-item:nth-child(6) { animation-delay: 0.6s; }

.pulse-animation {
    animation: pulse 2s infinite;
}

/* ========================================
   MOBILE RESPONSIVE DESIGN
======================================== */
@media (max-width: 768px) {
    .action-buttons {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    
    .nav-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    .nav-item {
        padding: 16px 12px;
    }
    
    .nav-item i {
        font-size: 24px;
    }
    
    .nav-item span {
        font-size: 11px;
    }
    
    .dashboard-item {
        margin-bottom: 16px;
    }
    
    .header-left .ammount {
        font-size: 24px;
    }
    
    .wallet-balance {
        font-size: 28px;
    }
    
    .stat-value {
        font-size: 20px;
    }
    
    /* Bottom Navigation for Mobile */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--secondary-dark);
        backdrop-filter: blur(20px);
        border-top: 1px solid var(--border-color);
        padding: 12px 0;
        z-index: 1000;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .bottom-nav-items {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
    
    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        color: var(--text-gray);
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
    }
    
    .bottom-nav-item.active,
    .bottom-nav-item:hover {
        color: var(--accent-blue);
        background: rgba(76, 111, 255, 0.1);
    }
    
    .bottom-nav-item i {
        font-size: 22px;
    }
    
    .bottom-nav-item span {
        font-size: 10px;
        font-weight: 500;
    }
}

/* ========================================
   DESKTOP SPECIFIC STYLES
======================================== */
@media (min-width: 769px) {
    .dashboard-sidebar {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        position: sticky;
        top: 20px;
    }
    
    .row.g-3 > div {
        margin-bottom: 0;
    }
    
    /* Hide mobile bottom nav on desktop */
    .bottom-nav {
        display: none;
    }
}

/* ========================================
   SIDEBAR STYLES
======================================== */
.dashboard-user {
    background: var(--gradient-orange-red) !important;
    border-radius: var(--radius-lg);
    position: relative;
    overflow: hidden;
}

.user-dashboard-tab li a {
    color: var(--text-light);
    padding: 14px 20px;
    border-radius: var(--radius-sm);
    margin-bottom: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-dashboard-tab li a:hover,
.user-dashboard-tab li a.active {
    background: rgba(76, 111, 255, 0.2);
    color: var(--text-white);
    transform: translateX(5px);
}

.user-dashboard-tab li a i {
    font-size: 18px;
}

/* ========================================
   REFERRAL URL SECTION
======================================== */
.referral-url-section {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 24px;
    margin-bottom: 24px;
}

.referral-url-section h6 {
    font-size: 14px;
    color: var(--text-gray);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.url-input-group {
    display: flex;
    gap: 12px;
}

.url-input-group input {
    flex: 1;
    background: var(--glass-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    color: var(--text-white);
    font-size: 14px;
}

.url-input-group input:focus {
    outline: none;
    border-color: var(--accent-blue);
    background: rgba(255, 255, 255, 0.08);
}

.copy-btn {
    background: linear-gradient(135deg, #f953c6 0%, #b91d73 100%);
    border: none;
    border-radius: var(--radius-sm);
    padding: 12px 24px;
    color: var(--text-white);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.copy-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(249, 83, 198, 0.4);
}

/* ========================================
   PROGRESS BARS
======================================== */
.progress {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    height: 10px;
    overflow: hidden;
}

.progress-bar {
    background: var(--gradient-green-blue);
    border-radius: 10px;
    transition: width 0.6s ease;
}

/* ========================================
   BADGES & LABELS
======================================== */
.badge-success {
    background: var(--gradient-teal-green);
    color: var(--text-white);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-warning {
    background: var(--gradient-yellow-orange);
    color: var(--text-white);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-danger {
    background: var(--gradient-pink-purple);
    color: var(--text-white);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* ========================================
   CUSTOM SCROLLBAR
======================================== */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--primary-dark);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>
