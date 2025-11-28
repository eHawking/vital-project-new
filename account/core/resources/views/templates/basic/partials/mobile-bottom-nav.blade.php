<!-- Mobile Bottom Navigation -->
<div class="bottom-nav d-md-none">
    <div class="bottom-nav-items">
        <a href="{{ route('user.home') }}" class="bottom-nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>@lang('Dashboard')</span>
        </a>
        <a href="{{ route('user.deposit.index') }}" class="bottom-nav-item {{ request()->routeIs('user.deposit*') ? 'active' : '' }}">
            <i class="bi bi-download"></i>
            <span>@lang('Deposit')</span>
        </a>
        <a href="{{ route('user.transactions') }}" class="bottom-nav-item {{ request()->routeIs('user.transactions') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>@lang('Activity')</span>
        </a>
        <a href="{{ route('user.my.ref') }}" class="bottom-nav-item {{ request()->routeIs('user.my.ref') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>@lang('Referral')</span>
        </a>
        <a href="{{ route('user.withdraw') }}" class="bottom-nav-item {{ request()->routeIs('user.withdraw*') ? 'active' : '' }}">
            <i class="bi bi-upload"></i>
            <span>@lang('Withdraw')</span>
        </a>
    </div>
</div>

<style>
/* Mobile Bottom Navigation Styles */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f953c6 100%);
    backdrop-filter: blur(20px);
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding: 12px 0 8px 0;
    z-index: 1000;
    box-shadow: 0 -8px 32px rgba(102, 126, 234, 0.4), 
                0 -4px 16px rgba(118, 75, 162, 0.3);
}

/* Add gradient overlay for depth */
.bottom-nav::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, transparent 100%);
    pointer-events: none;
}

.bottom-nav-items {
    display: flex;
    justify-content: space-around;
    align-items: center;
    max-width: 600px;
    margin: 0 auto;
}

.bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 8px 12px;
    border-radius: 12px;
    flex: 1;
    max-width: 80px;
    position: relative;
    z-index: 1;
}

/* Active state - clean without glow */
.bottom-nav-item.active {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transform: scale(1.02);
}

.bottom-nav-item:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.15);
    text-decoration: none;
    transform: translateY(-2px);
}

.bottom-nav-item i {
    font-size: 24px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    filter: drop-shadow(0 0 0 transparent);
}

/* Enhanced active icon - clean without glow */
.bottom-nav-item.active i {
    transform: scale(1.2);
    animation: icon-bounce 0.4s ease-out;
}

/* Icon bounce on activation - simplified */
@keyframes icon-bounce {
    0% { transform: scale(1) translateY(0); }
    50% { transform: scale(1.25) translateY(-4px); }
    100% { transform: scale(1.2) translateY(0); }
}

.bottom-nav-item span {
    font-size: 10px;
    font-weight: 600;
    text-transform: capitalize;
    letter-spacing: 0.3px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

/* Enhanced text for active state - clean */
.bottom-nav-item.active span {
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
}

/* Add padding to content on mobile to prevent overlap with bottom nav */
@media (max-width: 768px) {
    .user-dashboard {
        padding-bottom: 80px !important;
    }
}

/* Ripple effect container */
.bottom-nav-item::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    border-radius: 12px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
    opacity: 0;
    transform: scale(0);
    transition: all 0.5s ease;
    pointer-events: none;
}

.bottom-nav-item:active::after {
    opacity: 1;
    transform: scale(1.5);
    transition: all 0s;
}

/* Add shimmer effect to gradient bar */
@keyframes shimmer {
    0% {
        background-position: -200% center;
    }
    100% {
        background-position: 200% center;
    }
}

.bottom-nav::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.8) 50%, 
        transparent 100%);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
}
</style>

<script>
// Enhanced mobile navigation with active state management
(function() {
    'use strict';
    
    // Update active state based on current page
    const updateActiveState = () => {
        const currentPath = window.location.pathname;
        const navItems = document.querySelectorAll('.bottom-nav-item');
        
        navItems.forEach(item => {
            const href = item.getAttribute('href');
            const isActive = currentPath.includes(href.split('/').pop()) || 
                           (href.includes('user/home') && currentPath.endsWith('dashboard'));
            
            if (isActive && !item.classList.contains('active')) {
                item.classList.add('active');
                // Trigger bounce animation
                const icon = item.querySelector('i');
                if (icon) {
                    icon.style.animation = 'none';
                    setTimeout(() => {
                        icon.style.animation = '';
                    }, 10);
                }
            }
        });
    };
    
    // Add ripple effect on click
    const addRippleEffect = (e, element) => {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.6)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple-effect 0.6s ease-out';
        ripple.style.pointerEvents = 'none';
        
        element.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    };
    
    // Add ripple animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple-effect {
            to {
                transform: scale(2.5);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        updateActiveState();
        
        // Add click handlers
        document.querySelectorAll('.bottom-nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                addRippleEffect(e, item);
                
                // Remove active class from all items
                document.querySelectorAll('.bottom-nav-item').forEach(i => {
                    if (i !== item) i.classList.remove('active');
                });
                
                // Add active class to clicked item
                item.classList.add('active');
            });
        });
    });
    
    // Update on page navigation (for SPAs)
    window.addEventListener('popstate', updateActiveState);
    
    console.log('✨ Enhanced Mobile Navigation Initialized');
})();
</script>
