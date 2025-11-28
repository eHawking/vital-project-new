<style>
/* ========================================
   MOBILE FIXES & ENHANCEMENTS
   Back to Top & Icon Improvements
======================================== */

/* ========================================
   BACK TO TOP BUTTON - MOBILE FIX
======================================== */

/* Desktop position (default) */
.scrollToTop {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    text-align: center;
    line-height: 50px;
    border-radius: 50%;
    font-size: 24px;
    z-index: 999;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4),
                0 2px 10px rgba(118, 75, 162, 0.3);
    opacity: 0;
    visibility: hidden;
    transform: scale(0.8);
}

.scrollToTop.active {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
}

.scrollToTop:hover {
    background: linear-gradient(135deg, #764ba2 0%, #f953c6 100%);
    transform: scale(1.1) translateY(-3px);
    box-shadow: 0 6px 30px rgba(102, 126, 234, 0.6),
                0 4px 15px rgba(118, 75, 162, 0.4);
}

.scrollToTop i {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    animation: bounce-arrow 2s infinite;
}

@keyframes bounce-arrow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

/* Mobile position - Above bottom navigation */
@media (max-width: 768px) {
    .scrollToTop {
        bottom: 90px !important; /* Position above bottom nav (70px) + 20px gap */
        right: 20px;
        width: 45px;
        height: 45px;
        line-height: 45px;
        font-size: 20px;
        z-index: 999; /* Below bottom nav (1000) but above content */
    }
    
    .scrollToTop:hover {
        transform: scale(1.05) translateY(-2px);
    }
}

/* ========================================
   WALLET CARD BEAUTIFUL ICONS
======================================== */

/* Dashboard item header layout - icons on right */
.dashboard-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.dashboard-item-header .header-left {
    flex: 1;
}

.dashboard-item-header .right-content {
    flex-shrink: 0;
    margin-left: 20px;
}

/* Enhance wallet card icon containers */
.wallet-card .icon,
.dashboard-item .icon,
.dashboard-item .right-content .icon,
.right-content .icon {
    width: 80px !important;
    height: 80px !important;
    background: rgba(255, 255, 255, 0.15) !important;
    backdrop-filter: blur(10px) !important;
    border-radius: 14px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 36px !important;
    color: #ffffff !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    position: relative !important;
    overflow: visible !important;
}

/* Icon itself - direct class on div */
.icon.bi::before,
.icon[class*="bi-"]::before {
    font-family: 'bootstrap-icons' !important;
    font-size: 36px !important;
    color: #ffffff !important;
    display: inline-block !important;
    line-height: 1 !important;
    font-weight: 400 !important;
    font-style: normal !important;
    -webkit-font-smoothing: antialiased !important;
    -moz-osx-font-smoothing: grayscale !important;
}

.dashboard-item:hover .icon {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.15) rotate(5deg);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2),
                0 0 20px rgba(255, 255, 255, 0.3);
}

/* Icon glow effect */
.dashboard-item .icon::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 14px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.dashboard-item:hover .icon::before {
    opacity: 1;
}

/* Make Bootstrap Icons visible and beautiful */
.icon i,
.icon .bi,
.icon .las,
.icon .fa,
.icon .fab,
.icon .far,
.icon .fas {
    font-size: 36px !important;
    color: #ffffff !important;
    display: inline-block !important;
    line-height: 1 !important;
    font-style: normal !important;
    font-weight: 400 !important;
    transition: all 0.3s ease;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
}

/* Force Bootstrap Icons font family */
[class*="bi-"]::before,
.bi::before {
    font-family: 'bootstrap-icons' !important;
    font-size: inherit !important;
    display: inline-block !important;
    vertical-align: middle !important;
}

.dashboard-item:hover .icon i,
.dashboard-item:hover .icon .bi {
    transform: scale(1.1);
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3))
            drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
}

/* Specific icon colors for different card types */
.dashboard-item.gradient-card-1 .icon {
    background: rgba(255, 255, 255, 0.2);
}

.dashboard-item.gradient-card-2 .icon {
    background: rgba(255, 255, 255, 0.2);
}

.dashboard-item.gradient-card-3 .icon {
    background: rgba(255, 255, 255, 0.2);
}

.dashboard-item.gradient-card-4 .icon {
    background: rgba(255, 255, 255, 0.2);
}

.dashboard-item.gradient-card-5 .icon {
    background: rgba(255, 255, 255, 0.2);
}

.dashboard-item.gradient-card-6 .icon {
    background: rgba(255, 255, 255, 0.2);
}

/* Icon animation on card hover */
@keyframes icon-pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.dashboard-item:hover .icon i {
    animation: icon-pulse 0.6s ease-out;
}

/* Wallet card specific icon styling */
.wallet-card .action-btn i {
    font-size: 24px !important;
    color: var(--accent-blue) !important;
    display: inline-block !important;
    transition: all 0.3s ease;
}

.wallet-card .action-btn:hover i {
    transform: scale(1.2) rotate(5deg);
    filter: drop-shadow(0 0 8px rgba(76, 111, 255, 0.6));
}

/* Navigation item icons */
.nav-item i {
    font-size: 28px !important;
    color: var(--accent-cyan) !important;
    display: inline-block !important;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.nav-item:hover i {
    transform: scale(1.15) translateY(-3px);
    filter: drop-shadow(0 4px 8px rgba(6, 182, 212, 0.4));
}

/* Statistics card icons */
.stat-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 24px !important;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.stat-icon i {
    font-size: 24px !important;
    color: #ffffff !important;
    display: inline-block !important;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

/* Transaction item icons */
.transaction-icon {
    width: 44px !important;
    height: 44px !important;
    background: var(--gradient-purple-blue) !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 20px !important;
    color: #ffffff !important;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

.transaction-icon i {
    font-size: 20px !important;
    color: #ffffff !important;
    display: inline-block !important;
}

/* Ensure all icon fonts are loaded */
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css');

/* Force load Bootstrap Icons font */
@font-face {
    font-family: 'bootstrap-icons';
    src: url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/fonts/bootstrap-icons.woff2?dd67030699838ea613ee6dbda90effa6') format('woff2'),
         url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/fonts/bootstrap-icons.woff?dd67030699838ea613ee6dbda90effa6') format('woff');
    font-weight: normal;
    font-style: normal;
    font-display: block;
}

/* Force icon display */
[class*="bi-"]::before,
[class*="la-"]::before,
[class*="fa-"]::before {
    display: inline-block !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    -webkit-font-smoothing: antialiased !important;
}

/* Image icon fallback styling */
.icon img {
    width: 32px;
    height: 32px;
    object-fit: contain;
    filter: brightness(0) invert(1); /* Make white */
    transition: all 0.3s ease;
}

.dashboard-item:hover .icon img {
    transform: scale(1.1);
    filter: brightness(0) invert(1) drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));
}

/* Wallet and Dashboard Text Readability */
.dashboard-item .title {
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 600 !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
}

.dashboard-item .ammount,
.dashboard-item h3 {
    color: #ffffff !important;
    font-weight: 700 !important;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4) !important;
}

.wallet-card h6,
.wallet-card .title {
    color: rgba(255, 255, 255, 0.95) !important;
    font-weight: 600 !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
}

.wallet-card h3,
.wallet-card .balance {
    color: #ffffff !important;
    font-weight: 800 !important;
    text-shadow: 0 3px 8px rgba(0, 0, 0, 0.5) !important;
}

/* Ensure text is readable on gradient cards */
.dashboard-item.gradient-card-1 .title,
.dashboard-item.gradient-card-2 .title,
.dashboard-item.gradient-card-3 .title,
.dashboard-item.gradient-card-4 .title,
.dashboard-item.gradient-card-5 .title,
.dashboard-item.gradient-card-6 .title {
    color: rgba(255, 255, 255, 1) !important;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6) !important;
}

.dashboard-item.gradient-card-1 .ammount,
.dashboard-item.gradient-card-2 .ammount,
.dashboard-item.gradient-card-3 .ammount,
.dashboard-item.gradient-card-4 .ammount,
.dashboard-item.gradient-card-5 .ammount,
.dashboard-item.gradient-card-6 .ammount {
    color: #ffffff !important;
    font-weight: 900 !important;
    text-shadow: 0 3px 10px rgba(0, 0, 0, 0.7) !important;
}

/* Mobile responsive icon sizes */
@media (max-width: 768px) {
    .dashboard-item .icon,
    .wallet-card .icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .icon i,
    .icon .bi {
        font-size: 24px !important;
    }
    
    .nav-item i {
        font-size: 24px !important;
    }
    
    .stat-icon {
        width: 44px !important;
        height: 44px !important;
    }
    
    .stat-icon i {
        font-size: 20px !important;
    }
}

/* Add glow animation for important icons */
@keyframes icon-glow {
    0%, 100% {
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
    }
    50% {
        filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.6));
    }
}

.dashboard-item.gradient-card-1 .icon i,
.dashboard-item.gradient-card-2 .icon i,
.dashboard-item.gradient-card-3 .icon i {
    animation: icon-glow 3s ease-in-out infinite;
}

/* Wallet balance icon special styling */
.bi-wallet,
.bi-wallet2,
.bi-currency-dollar,
.bi-cash-stack {
    font-size: 30px !important;
}

/* Loading state for icons */
.icon.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
