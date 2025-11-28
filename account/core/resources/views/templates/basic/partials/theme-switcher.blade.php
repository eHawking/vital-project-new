<!-- Theme Switcher Component -->
<div class="theme-switcher-container">
    <button class="theme-switcher-btn" id="themeSwitcher" aria-label="Toggle theme">
        <div class="theme-switcher-icons">
            <i class="bi bi-sun-fill light-icon"></i>
            <i class="bi bi-moon-fill dark-icon"></i>
        </div>
        <span class="theme-label">
            <span class="light-label">Light</span>
            <span class="dark-label">Dark</span>
        </span>
    </button>
</div>

<style>
/* Theme Switcher Styles */
.theme-switcher-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.theme-switcher-btn {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.theme-switcher-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
    border-color: var(--accent-blue);
}

.theme-switcher-icons {
    position: relative;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-switcher-icons i {
    position: absolute;
    font-size: 18px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.light-icon {
    color: #fbbf24;
    opacity: 0;
    transform: rotate(-180deg) scale(0);
}

.dark-icon {
    color: #f1f5f9;
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

/* Light theme active state */
[data-theme="light"] .light-icon {
    opacity: 1;
    transform: rotate(0deg) scale(1);
}

[data-theme="light"] .dark-icon {
    opacity: 0;
    transform: rotate(180deg) scale(0);
}

.theme-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-white);
    position: relative;
    width: 45px;
    height: 20px;
    display: flex;
    align-items: center;
}

.theme-label span {
    position: absolute;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.light-label {
    opacity: 0;
    transform: translateY(10px);
}

.dark-label {
    opacity: 1;
    transform: translateY(0);
}

/* Light theme label */
[data-theme="light"] .light-label {
    opacity: 1;
    transform: translateY(0);
}

[data-theme="light"] .dark-label {
    opacity: 0;
    transform: translateY(-10px);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .theme-switcher-container {
        top: 10px;
        right: 10px;
    }
    
    .theme-switcher-btn {
        padding: 8px 16px;
    }
    
    .theme-label {
        display: none;
    }
    
    .theme-switcher-icons {
        width: 20px;
        height: 20px;
    }
    
    .theme-switcher-icons i {
        font-size: 16px;
    }
}

/* Pulse animation on theme change */
@keyframes themePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.theme-switcher-btn.pulse {
    animation: themePulse 0.3s ease;
}
</style>

<script>
// Theme Switcher JavaScript
(function() {
    'use strict';
    
    const themeSwitcher = document.getElementById('themeSwitcher');
    const htmlElement = document.documentElement;
    
    // Get stored theme or default to dark
    const getStoredTheme = () => localStorage.getItem('theme') || 'dark';
    const setStoredTheme = (theme) => localStorage.setItem('theme', theme);
    
    // Apply theme
    const applyTheme = (theme, animate = false) => {
        htmlElement.setAttribute('data-theme', theme);
        
        // Add pulse animation
        if (animate && themeSwitcher) {
            themeSwitcher.classList.add('pulse');
            setTimeout(() => themeSwitcher.classList.remove('pulse'), 300);
        }
        
        // Update meta theme color for mobile browsers
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', theme === 'light' ? '#f8fafc' : '#0a1f3d');
        }
        
        console.log('✨ Theme switched to:', theme);
    };
    
    // Initialize theme on page load
    const initTheme = () => {
        const storedTheme = getStoredTheme();
        applyTheme(storedTheme, false);
    };
    
    // Toggle theme
    const toggleTheme = () => {
        const currentTheme = htmlElement.getAttribute('data-theme') || 'dark';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        applyTheme(newTheme, true);
        setStoredTheme(newTheme);
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
    };
    
    // Event listener
    if (themeSwitcher) {
        themeSwitcher.addEventListener('click', toggleTheme);
    }
    
    // Initialize on DOM load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }
    
    // Keyboard shortcut: Ctrl/Cmd + Shift + T
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
            e.preventDefault();
            toggleTheme();
        }
    });
})();
</script>
