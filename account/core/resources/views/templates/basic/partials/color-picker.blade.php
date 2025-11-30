<!-- Theme Settings Modal (Replaces Drawer) -->
<div class="modal fade" id="themeSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card, #1e293b); border: 1px solid var(--border-card, rgba(255,255,255,0.1)); backdrop-filter: blur(20px);">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">Theme Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="setting-group">
                    <h6 class="text-white-50 mb-3 text-uppercase small ls-1">Color Theme</h6>
                    <div class="theme-options">
                        <button class="theme-option active" data-theme-color="royal-purple" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);"></button>
                        <button class="theme-option" data-theme-color="ocean-blue" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);"></button>
                        <button class="theme-option" data-theme-color="emerald-green" style="background: linear-gradient(135deg, #10b981, #059669);"></button>
                        <button class="theme-option" data-theme-color="sunset-orange" style="background: linear-gradient(135deg, #f59e0b, #d97706);"></button>
                        <button class="theme-option" data-theme-color="ruby-red" style="background: linear-gradient(135deg, #ef4444, #dc2626);"></button>
                        <button class="theme-option" data-theme-color="luxury-gold" style="background: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728);"></button>
                        <button class="theme-option" data-theme-color="deep-space" style="background: linear-gradient(135deg, #0f172a, #334155); border: 1px solid rgba(255,255,255,0.2);"></button>
                        <button class="theme-option" data-theme-color="neon-cyber" style="background: linear-gradient(135deg, #ff00cc, #333399);"></button>
                        <button class="theme-option" data-theme-color="midnight-rose" style="background: linear-gradient(135deg, #881111, #550000);"></button>
                        
                        <!-- Super Premium Colors -->
                        <button class="theme-option" data-theme-color="aurora" style="background: linear-gradient(135deg, #00c6ff, #0072ff);"></button>
                        <button class="theme-option" data-theme-color="plasma" style="background: linear-gradient(135deg, #4b6cb7, #182848);"></button>
                        <button class="theme-option" data-theme-color="golden-hour" style="background: linear-gradient(135deg, #ff512f, #f09819);"></button>
                        <button class="theme-option" data-theme-color="crimson-king" style="background: linear-gradient(135deg, #bdc3c7, #2c3e50);"></button>
                    </div>
                </div>
                
                <div class="setting-group mt-4">
                    <h6 class="text-white-50 mb-3 text-uppercase small ls-1">Mode</h6>
                    <div class="mode-switch-container">
                        <button class="mode-btn active" data-mode="dark"><i class="bi bi-moon-stars-fill"></i> Dark</button>
                        <button class="mode-btn" data-mode="light"><i class="bi bi-sun-fill"></i> Light</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Toggle Button -->
<div class="theme-settings-toggle" id="themeSettingsToggle">
    <i class="bi bi-palette-fill"></i>
</div>

<style>
    .theme-settings-toggle {
        position: fixed;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
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
        z-index: 9999;
        transition: all 0.3s ease;
    }

    .theme-settings-toggle:hover {
        padding-right: 10px;
    }
    
    .theme-options {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    }
    
    .theme-option:hover {
        transform: scale(1.1);
    }
    
    .theme-option.active {
        border-color: white;
        transform: scale(1.1);
        box-shadow: 0 0 15px var(--color-primary);
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
        background: var(--bg-body);
        color: var(--color-primary);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<script>
(function() {
    const toggle = document.getElementById('themeSettingsToggle');
    const themeBtns = document.querySelectorAll('.theme-option');
    const modeBtns = document.querySelectorAll('.mode-btn');
    let modal = null;

    // Initialize Modal
    function getModal() {
        if (!modal && window.bootstrap) {
            const el = document.getElementById('themeSettingsModal');
            if (el) modal = new bootstrap.Modal(el);
        }
        return modal;
    }
    
    if(toggle) {
        toggle.addEventListener('click', function() {
            const m = getModal();
            if (m) m.show();
        });
    }

    // Allow external triggers (like from dashboard header)
    document.addEventListener('click', function(e) {
        if (e.target.closest('#dashboardThemeToggle')) {
            const m = getModal();
            if (m) m.show();
        }
    });
    
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
        },
        'deep-space': {
            primary: '#3b82f6',
            secondary: '#1e293b',
            grad: 'linear-gradient(135deg, #0f172a 0%, #334155 100%)',
            glow: 'radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent 40%)'
        },
        'neon-cyber': {
            primary: '#d946ef',
            secondary: '#8b5cf6',
            grad: 'linear-gradient(135deg, #ff00cc 0%, #333399 100%)',
            glow: 'radial-gradient(circle at top right, rgba(217, 70, 239, 0.2), transparent 40%)'
        },
        'midnight-rose': {
            primary: '#f43f5e',
            secondary: '#881337',
            grad: 'linear-gradient(135deg, #881111 0%, #550000 100%)',
            glow: 'radial-gradient(circle at top right, rgba(244, 63, 94, 0.15), transparent 40%)'
        },
        'aurora': {
            primary: '#00c6ff',
            secondary: '#0072ff',
            grad: 'linear-gradient(135deg, #00c6ff 0%, #0072ff 100%)',
            glow: 'radial-gradient(circle at top right, rgba(0, 198, 255, 0.2), transparent 40%)'
        },
        'plasma': {
            primary: '#4b6cb7',
            secondary: '#182848',
            grad: 'linear-gradient(135deg, #4b6cb7 0%, #182848 100%)',
            glow: 'radial-gradient(circle at top right, rgba(75, 108, 183, 0.2), transparent 40%)'
        },
        'golden-hour': {
            primary: '#ff512f',
            secondary: '#f09819',
            grad: 'linear-gradient(135deg, #ff512f 0%, #f09819 100%)',
            glow: 'radial-gradient(circle at top right, rgba(255, 81, 47, 0.2), transparent 40%)'
        },
        'crimson-king': {
            primary: '#bdc3c7',
            secondary: '#2c3e50',
            grad: 'linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%)',
            glow: 'radial-gradient(circle at top right, rgba(189, 195, 199, 0.2), transparent 40%)'
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
    
    // Mode Switch
    modeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const mode = btn.dataset.mode;
            const htmlElement = document.documentElement;
            const currentTheme = htmlElement.getAttribute('data-theme');
            
            if (currentTheme !== mode) {
                const existingSwitcher = document.getElementById('themeSwitcher');
                if (existingSwitcher) existingSwitcher.click();
                else {
                    htmlElement.setAttribute('data-theme', mode);
                    localStorage.setItem('theme', mode);
                }
            }
            
            modeBtns.forEach(b => b.classList.toggle('active', b.dataset.mode === mode));
        });
    });
    
    // Sync mode buttons
    const currentTheme = localStorage.getItem('theme') || 'dark';
    modeBtns.forEach(b => b.classList.toggle('active', b.dataset.mode === currentTheme));
})();
</script>
