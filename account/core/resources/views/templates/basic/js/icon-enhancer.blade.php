<script>
// Icon Display Enhancer
// Ensures all icons display properly and beautifully
(function() {
    'use strict';
    
    // Initialize icon display enhancements
    const ensureBootstrapIcon = (icon, iconName) => {
        if (!icon) {
            return;
        }

        if (!icon.classList.contains('bi')) {
            icon.classList.add('bi');
        }

        // Remove any existing bootstrap icon variants before applying the new one
        icon.classList.forEach(cls => {
            if (cls.startsWith('bi-')) {
                icon.classList.remove(cls);
            }
        });

        if (iconName) {
            iconName.trim().split(/\s+/).forEach(cls => {
                if (!cls) {
                    return;
                }

                if (cls === 'bi') {
                    icon.classList.add('bi');
                } else if (cls.startsWith('bi-')) {
                    icon.classList.add(cls);
                } else {
                    icon.classList.add(`bi-${cls}`);
                }
            });
        }
    };

    const loadBootstrapIconsFromCDN = () => {
        if (document.querySelector('link[data-icon-enhancer="bootstrap-icons"]')) {
            return;
        }

        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css';
        link.crossOrigin = 'anonymous';
        link.dataset.iconEnhancer = 'bootstrap-icons';
        document.head.appendChild(link);
        console.log('✓ Bootstrap Icons CSS loaded from CDN');
    };

    const iconGlyphAvailable = (iconClass) => {
        if (!document.body) {
            return false;
        }

        const tester = document.createElement('i');
        tester.className = `bi ${iconClass}`;
        tester.style.position = 'absolute';
        tester.style.left = '-9999px';
        tester.style.top = '0';
        document.body.appendChild(tester);

        const content = window.getComputedStyle(tester, '::before').getPropertyValue('content');
        document.body.removeChild(tester);

        if (!content || content === 'none') {
            return false;
        }

        const sanitized = content.replace(/"/g, '').trim();
        return sanitized !== '';
    };

    const ensureBootstrapIconsCSS = () => {
        const attachAfterDomReady = () => {
            const existingLinks = Array.from(document.querySelectorAll('link[href*="bootstrap-icons"]'));

            if (!existingLinks.length) {
                loadBootstrapIconsFromCDN();
                return;
            }

            const walletSupported = iconGlyphAvailable('bi-wallet');
            const walletTwoSupported = iconGlyphAvailable('bi-wallet2');

            if (!walletSupported || !walletTwoSupported) {
                console.warn('Detected Bootstrap Icons without wallet glyphs. Fetching updated icon set.');
                loadBootstrapIconsFromCDN();
            } else {
                console.log('✓ Bootstrap Icons detected with wallet glyph support.');
            }
        };

        if (!document.body) {
            document.addEventListener('DOMContentLoaded', attachAfterDomReady, { once: true });
        } else {
            attachAfterDomReady();
        }
    };

    const initIconEnhancer = () => {
        // Find all icon containers
        const iconContainers = document.querySelectorAll('.icon, .stat-icon, .transaction-icon');
        
        iconContainers.forEach(container => {
            // Check if icon has proper content
            let icon = container.querySelector('i, .bi, .las, .fa, img');
            
            // If no <i> tag but container has bi- class, convert it
            if (!icon && container.className.includes('bi-')) {
                const iconClass = container.className;
                container.className = 'icon';
                icon = document.createElement('i');
                ensureBootstrapIcon(icon, iconClass);
                container.appendChild(icon);
                console.log('Converted icon class to i tag:', iconClass);
            }
            
            if (!icon && !container.textContent.trim() && !container.querySelector('img')) {
                // No icon found, add a default beautiful icon
                const defaultIcon = document.createElement('i');
                defaultIcon.className = 'bi bi-star-fill';
                container.appendChild(defaultIcon);
                console.log('Added default icon to empty container');
            }
            
            // Ensure proper display properties
            if (icon) {
                icon.style.display = 'inline-block';
                icon.style.lineHeight = '1';
                icon.style.fontFamily = '"Bootstrap Icons", bootstrap-icons';
            }
        });
        
        // Enhance dashboard item icons
        const dashboardItems = document.querySelectorAll('.dashboard-item');
        dashboardItems.forEach((item, index) => {
            const icon = item.querySelector('.icon i, .icon .bi');
            
            if (icon) {
                // Make sure icon is visible
                icon.style.opacity = '1';
                icon.style.visibility = 'visible';
                
                // Add specific icon if missing or generic
                if (!icon.className || icon.className.includes('bi-') === false) {
                    // Assign beautiful finance-related icons
                    const financeIcons = [
                        'bi-wallet2',
                        'bi-credit-card-2-front',
                        'bi-graph-up-arrow',
                        'bi-cash-stack',
                        'bi-currency-dollar',
                        'bi-bank',
                        'bi-piggy-bank',
                        'bi-coin',
                        'bi-trophy',
                        'bi-gift'
                    ];
                    ensureBootstrapIcon(icon, `bi ${financeIcons[index % financeIcons.length]}`);
                }
            }
        });
        
        console.log('✨ Icon Enhancer Initialized - All icons beautified!');
    };
    
    // Fallback icon mapping for specific dashboard items
    const iconMapping = {
        'Current Balance': 'bi-wallet2',
        'E-Pin Credit': 'bi-credit-card-2-front',
        'DSP Ref Bonus': 'bi-clipboard-check',
        'Royalty Bonus': 'bi-star-fill',
        'Pair Bonus': 'bi-briefcase-fill',
        'DDS Ref Bonus': 'bi-bag-plus-fill',
        'Shop Bonus': 'bi-shop',
        'Franchise Bonus': 'bi-building',
        'Weekly Bonus': 'bi-calendar-check',
        'BV': 'bi-award',
        'PV': 'bi-gem',
        'Total Deposit': 'bi-download',
        'Total Withdraw': 'bi-upload',
        'Total Investment': 'bi-bar-chart-line-fill',
        'Total Profit': 'bi-graph-up-arrow',
        'Referral': 'bi-people-fill',
        'Transactions': 'bi-arrow-left-right'
    };
    
    // Apply icon mapping to dashboard items
    const applyIconMapping = () => {
        document.querySelectorAll('.dashboard-item').forEach(item => {
            const titleElement = item.querySelector('.title');
            if (titleElement) {
                const title = titleElement.textContent.trim();
                const iconContainer = item.querySelector('.icon');
                
                if (iconContainer && iconMapping[title]) {
                    let icon = iconContainer.querySelector('i');
                    if (!icon) {
                        icon = document.createElement('i');
                        iconContainer.appendChild(icon);
                    }
                    ensureBootstrapIcon(icon, iconMapping[title]);
                    console.log(`Applied icon ${iconMapping[title]} to ${title}`);
                }
            }
        });
    };
    
    // Check if icons are rendering
    const checkIconRendering = () => {
        setTimeout(() => {
            const icons = document.querySelectorAll('.bi, .las, .fa');
            icons.forEach(icon => {
                const computed = window.getComputedStyle(icon);
                if (computed.fontFamily.includes('bootstrap-icons') || 
                    computed.fontFamily.includes('Line Awesome') ||
                    computed.fontFamily.includes('Font Awesome')) {
                    // Icon font is loaded
                    icon.style.opacity = '1';
                } else {
                    console.warn('Icon font not loaded for:', icon.className);
                }
            });
        }, 500);
    };
    
    // Add pulsing effect to important icons
    const addPulsingEffect = () => {
        const importantIcons = document.querySelectorAll(
            '.wallet-card .icon, .dashboard-item:first-child .icon'
        );
        
        importantIcons.forEach(icon => {
            icon.style.animation = 'icon-pulse 2s ease-in-out infinite';
        });
    };
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            ensureBootstrapIconsCSS();
            initIconEnhancer();
            applyIconMapping();
            checkIconRendering();
            addPulsingEffect();
        });
    } else {
        ensureBootstrapIconsCSS();
        initIconEnhancer();
        applyIconMapping();
        checkIconRendering();
        addPulsingEffect();
    }
    
    // Re-check after a delay for dynamically loaded content
    setTimeout(() => {
        initIconEnhancer();
        applyIconMapping();
    }, 1000);
    
})();
</script>
