<div class="theme-settings-drawer" id="themeSettingsDrawer">
    <div class="theme-settings-toggle" id="themeSettingsToggle">
        <i class="bi bi-palette-fill"></i>
    </div>
    
    <div class="theme-settings-header">
        <h5>Theme Settings</h5>
        <button class="close-btn" id="closeThemeSettings"><i class="bi bi-x-lg"></i></button>
    </div>
    
    <div class="theme-settings-body">
        <div class="setting-group">
            <h6>Color Theme</h6>
            <div class="theme-options">
                <button class="theme-option active" data-theme-color="royal-purple" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);"></button>
                <button class="theme-option" data-theme-color="ocean-blue" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);"></button>
                <button class="theme-option" data-theme-color="emerald-green" style="background: linear-gradient(135deg, #10b981, #059669);"></button>
                <button class="theme-option" data-theme-color="sunset-orange" style="background: linear-gradient(135deg, #f59e0b, #d97706);"></button>
                <button class="theme-option" data-theme-color="ruby-red" style="background: linear-gradient(135deg, #ef4444, #dc2626);"></button>
                <button class="theme-option" data-theme-color="luxury-gold" style="background: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728);"></button>
            </div>
        </div>
        
        <div class="setting-group">
            <h6>Mode</h6>
            <div class="mode-switch-container">
                <button class="mode-btn active" data-mode="dark"><i class="bi bi-moon-stars-fill"></i> Dark</button>
                <button class="mode-btn" data-mode="light"><i class="bi bi-sun-fill"></i> Light</button>
            </div>
        </div>
    </div>
</div>

<style>
    .theme-settings-drawer {
        position: fixed;
        top: 0;
        right: -350px; /* Increased width */
        width: 350px;
        height: 100vh;
        background: var(--bg-sidebar, #1e293b); /* Match sidebar exactly */
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 1px solid var(--border-card, rgba(255,255,255,0.1));
        display: flex;
        flex-direction: column;
        border-radius: 0 !important; /* Remove radius */
    }
    
    /* Mobile Full Width */
    @media (max-width: 768px) {
        .theme-settings-drawer {
            width: 100%;
            right: -100%;
        }
    }

    .theme-settings-drawer.open {
        right: 0;
    }
    
    .theme-settings-toggle {
        position: absolute;
        left: -50px;
        top: 200px;
        width: 50px;
        height: 50px;
        background: var(--color-primary, #6366f1);
        border-radius: 12px 0 0 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
        color: white;
        font-size: 1.2rem;
    }
    
    .theme-settings-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-card, rgba(255,255,255,0.1));
    }
    
    .theme-settings-header h5 {
        margin: 0;
        color: var(--text-primary);
        font-weight: 700;
    }
    
    .close-btn {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 1.2rem;
    }
    
    .theme-settings-body {
        padding: 20px;
        flex: 1;
        overflow-y: auto;
    }
    
    .setting-group {
        margin-bottom: 30px;
    }
    
    .setting-group h6 {
        color: var(--text-secondary);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    
    .theme-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    
    .theme-option {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 50%;
        border: 3px solid transparent;
        cursor: pointer;
        transition: transform 0.2s;
        position: relative;
    }
    
    .theme-option:hover {
        transform: scale(1.1);
    }
    
    .theme-option.active {
        border-color: var(--text-primary);
        transform: scale(1.1);
    }
    
    .mode-switch-container {
        display: flex;
        background: rgba(255,255,255,0.05);
        padding: 5px;
        border-radius: 12px;
        gap: 5px;
    }
    
    .mode-btn {
        flex: 1;
        background: transparent;
        border: none;
        padding: 10px;
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .mode-btn.active {
        background: var(--bg-card);
        color: var(--color-primary);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<script>
(function() {
    const drawer = document.getElementById('themeSettingsDrawer');
    const toggle = document.getElementById('themeSettingsToggle');
    const closeBtn = document.getElementById('closeThemeSettings');
    const themeBtns = document.querySelectorAll('.theme-option');
    const modeBtns = document.querySelectorAll('.mode-btn');
    
    // Drawer Toggle
    function toggleDrawer() {
        drawer.classList.toggle('open');
    }
    
    if(toggle) toggle.addEventListener('click', toggleDrawer);
    if(closeBtn) closeBtn.addEventListener('click', toggleDrawer);
    
    // Theme Color Selection
    const themes = {
        'royal-purple': {
            primary: '#6366f1',
            secondary: '#8b5cf6',
            grad: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            glow: 'radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%)'
        },
        'ocean-blue': {
            primary: '#3b82f6',
            secondary: '#06b6d4',
            grad: 'linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%)',
            glow: 'radial-gradient(circle at top right, rgba(59, 130, 246, 0.15), transparent 40%)'
        },
        'emerald-green': {
            primary: '#10b981',
            secondary: '#059669',
            grad: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            glow: 'radial-gradient(circle at top right, rgba(16, 185, 129, 0.15), transparent 40%)'
        },
        'sunset-orange': {
            primary: '#f59e0b',
            secondary: '#d97706',
            grad: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            glow: 'radial-gradient(circle at top right, rgba(245, 158, 11, 0.15), transparent 40%)'
        },
        'ruby-red': {
            primary: '#ef4444',
            secondary: '#dc2626',
            grad: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            glow: 'radial-gradient(circle at top right, rgba(239, 68, 68, 0.15), transparent 40%)'
        },
        'luxury-gold': {
            primary: '#bf953f',
            secondary: '#b38728',
            grad: 'linear-gradient(135deg, #bf953f 0%, #fcf6ba 50%, #b38728 100%)',
            glow: 'radial-gradient(circle at top right, rgba(191, 149, 63, 0.15), transparent 40%)'
        }
    };
    
    function applyTheme(themeName) {
        const theme = themes[themeName];
        if (!theme) return;
        
        document.documentElement.style.setProperty('--color-primary', theme.primary);
        document.documentElement.style.setProperty('--color-secondary', theme.secondary);
        document.documentElement.style.setProperty('--grad-primary', theme.grad);
        document.documentElement.style.setProperty('--grad-glow', theme.glow);
        
        // Update active state
        themeBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.themeColor === themeName);
        });
        
        localStorage.setItem('user-theme-color', themeName);
    }
    
    themeBtns.forEach(btn => {
        btn.addEventListener('click', () => applyTheme(btn.dataset.themeColor));
    });
    
    // Load saved theme
    const savedTheme = localStorage.getItem('user-theme-color');
    if (savedTheme) applyTheme(savedTheme);
    
    // Mode Switch (Integration with existing theme switcher)
    modeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const mode = btn.dataset.mode;
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-theme');
            
            if (currentTheme !== mode) {
                // Trigger existing theme switcher if available
                const existingSwitcher = document.getElementById('themeSwitcher');
                if (existingSwitcher) existingSwitcher.click();
                else {
                    // Fallback manual switch
                    htmlElement.setAttribute('data-theme', mode);
                    localStorage.setItem('theme', mode);
                }
            }
            
            // Update active states
            modeBtns.forEach(b => b.classList.toggle('active', b.dataset.mode === mode));
        });
    });
    
    // Sync mode buttons with current theme
    const currentTheme = localStorage.getItem('theme') || 'dark';
    modeBtns.forEach(b => b.classList.toggle('active', b.dataset.mode === currentTheme));
})();
</script>
