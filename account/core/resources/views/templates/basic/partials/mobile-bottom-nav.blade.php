<!-- Mobile Bottom Navigation (Floating Island Style) -->
<div class="bottom-nav-wrapper d-md-none">
    <div class="bottom-nav">
        <a href="{{ route('user.home') }}" class="nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
            <div class="icon-container">
                <i class="bi bi-grid-fill"></i>
            </div>
            <span>Home</span>
        </a>
        
        <a href="{{ route('user.deposit.index') }}" class="nav-item {{ request()->routeIs('user.deposit*') ? 'active' : '' }}">
            <div class="icon-container">
                <i class="bi bi-wallet2"></i>
            </div>
            <span>Deposit</span>
        </a>
        
        <!-- Central Floating Action Button (Invest/Trade) -->
        <div class="nav-item center-fab">
            <a href="{{ route('user.plan.index') }}" class="fab-button">
                <i class="bi bi-lightning-charge-fill"></i>
            </a>
        </div>
        
        <a href="{{ route('user.withdraw') }}" class="nav-item {{ request()->routeIs('user.withdraw*') ? 'active' : '' }}">
            <div class="icon-container">
                <i class="bi bi-cash-stack"></i>
            </div>
            <span>Withdraw</span>
        </a>
        
        <a href="{{ route('user.transactions') }}" class="nav-item {{ request()->routeIs('user.transactions') ? 'active' : '' }}">
            <div class="icon-container">
                <i class="bi bi-clock-history"></i>
            </div>
            <span>History</span>
        </a>
    </div>
</div>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top d-md-none">
    <i class="bi bi-arrow-up-short"></i>
</button>

<style>
/* --- Mobile Nav Wrapper --- */
.bottom-nav-wrapper {
    position: fixed;
    bottom: 20px;
    left: 0;
    right: 0;
    z-index: 1000;
    padding: 0 10px; /* Match dashboard padding */
    pointer-events: none; /* Allow clicks to pass through wrapper */
}

/* --- Floating Glass Bar --- */
.bottom-nav {
    background: rgba(15, 23, 42, 0.85); /* Fallback */
    background: var(--bg-sidebar);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--border-card);
    border-radius: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3);
    pointer-events: auto; /* Re-enable clicks */
    position: relative;
    max-width: 100%; /* Full width */
    width: 100%;
    margin: 0 auto;
}

/* --- Nav Items --- */
.nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: var(--text-secondary);
    font-size: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    gap: 4px;
    flex: 1;
}

.nav-item .icon-container {
    font-size: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.nav-item span {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

/* --- Active State --- */
.nav-item.active {
    color: var(--color-primary);
}

.nav-item.active .icon-container {
    transform: translateY(-2px);
    color: var(--color-primary);
    filter: drop-shadow(0 0 8px var(--color-primary));
}

.nav-item.active span {
    opacity: 1;
    color: var(--text-primary);
}

/* --- Center FAB (Floating Action Button) --- */
.center-fab {
    position: relative;
    top: -25px;
    flex: 0 0 auto;
    margin: 0 10px;
}

.fab-button {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--grad-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 8px 20px -5px var(--color-primary);
    border: 4px solid var(--bg-body); /* Cutout effect */
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.fab-button:hover, .fab-button:active {
    transform: scale(1.1) rotate(15deg);
    color: white;
}

/* --- Back to Top Button --- */
.back-to-top {
    position: fixed;
    bottom: 100px; /* Above nav */
    right: 20px;
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-card);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    cursor: pointer;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 999;
    box-shadow: var(--shadow-card);
}

.back-to-top.visible {
    opacity: 1;
    transform: translateY(0);
}

.back-to-top:hover {
    background: var(--grad-primary);
    color: white;
    border-color: transparent;
}

/* --- Bottom Padding Fix for Layout --- */
@media (max-width: 768px) {
    body {
        padding-bottom: 100px !important; /* Ensure content isn't hidden */
    }
    
    /* Disable default browser scroll chaining if needed */
    .bottom-nav {
        overscroll-behavior: contain;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Back to Top Logic
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });
    
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Add ripple effect to Nav Items
    const navItems = document.querySelectorAll('.nav-item:not(.center-fab)');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Remove active from siblings
            navItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
