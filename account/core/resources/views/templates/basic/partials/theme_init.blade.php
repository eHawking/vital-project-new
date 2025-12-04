<script>
    (function() {
        // 1. Restore Dark/Light Mode
        const savedMode = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedMode);
    
        // 2. Restore Color Theme
        const savedColor = localStorage.getItem('user-theme-color');
        if(savedColor) {
            const themes = {
                'royal-purple': { primary: '#6366f1', secondary: '#8b5cf6', grad: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)', glow: 'radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 40%)' },
                'ocean-blue': { primary: '#3b82f6', secondary: '#06b6d4', grad: 'linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%)', glow: 'radial-gradient(circle at top right, rgba(59, 130, 246, 0.15), transparent 40%)' },
                'emerald-green': { primary: '#10b981', secondary: '#059669', grad: 'linear-gradient(135deg, #10b981 0%, #059669 100%)', glow: 'radial-gradient(circle at top right, rgba(16, 185, 129, 0.15), transparent 40%)' },
                'sunset-orange': { primary: '#f59e0b', secondary: '#d97706', grad: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', glow: 'radial-gradient(circle at top right, rgba(245, 158, 11, 0.15), transparent 40%)' },
                'ruby-red': { primary: '#ef4444', secondary: '#dc2626', grad: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', glow: 'radial-gradient(circle at top right, rgba(239, 68, 68, 0.15), transparent 40%)' },
                'luxury-gold': { primary: '#bf953f', secondary: '#b38728', grad: 'linear-gradient(135deg, #bf953f 0%, #fcf6ba 50%, #b38728 100%)', glow: 'radial-gradient(circle at top right, rgba(191, 149, 63, 0.15), transparent 40%)' },
                'neon-pink': { primary: '#ec4899', secondary: '#db2777', grad: 'linear-gradient(135deg, #ec4899 0%, #db2777 100%)', glow: 'radial-gradient(circle at top right, rgba(236, 72, 153, 0.15), transparent 40%)' },
                'electric-cyan': { primary: '#06b6d4', secondary: '#0891b2', grad: 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)', glow: 'radial-gradient(circle at top right, rgba(6, 182, 212, 0.15), transparent 40%)' },
                'cyber-lime': { primary: '#84cc16', secondary: '#65a30d', grad: 'linear-gradient(135deg, #84cc16 0%, #65a30d 100%)', glow: 'radial-gradient(circle at top right, rgba(132, 204, 22, 0.15), transparent 40%)' },
                'phantom-black': { primary: '#94a3b8', secondary: '#475569', grad: 'linear-gradient(135deg, #334155 0%, #0f172a 100%)', glow: 'radial-gradient(circle at top right, rgba(255, 255, 255, 0.1), transparent 40%)' },
                'plasma-violet': { primary: '#a78bfa', secondary: '#7c3aed', grad: 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)', glow: 'radial-gradient(circle at top right, rgba(139, 92, 246, 0.15), transparent 40%)' },
                'solar-flare': { primary: '#facc15', secondary: '#eab308', grad: 'linear-gradient(135deg, #facc15 0%, #ca8a04 100%)', glow: 'radial-gradient(circle at top right, rgba(250, 204, 21, 0.15), transparent 40%)' }
            };
            
            const theme = themes[savedColor];
            if (theme) {
                document.documentElement.style.setProperty('--color-primary', theme.primary);
                document.documentElement.style.setProperty('--color-secondary', theme.secondary);
                document.documentElement.style.setProperty('--grad-primary', theme.grad);
                document.documentElement.style.setProperty('--grad-glow', theme.glow);
            }
        }
    })();
</script>
