<script>
// Modern Finance Dashboard Initialization
document.addEventListener('DOMContentLoaded', function() {
    
    // Apply gradient classes to dashboard items in order
    const gradientClasses = [
        'gradient-card-1', 'gradient-card-2', 'gradient-card-3', 
        'gradient-card-4', 'gradient-card-5', 'gradient-card-6'
    ];
    
    // Apply gradients to all dashboard items
    const dashboardItems = document.querySelectorAll('.dashboard-item');
    dashboardItems.forEach((item, index) => {
        const gradientClass = gradientClasses[index % gradientClasses.length];
        item.classList.add(gradientClass);
    });
    
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all dashboard items
    document.querySelectorAll('.dashboard-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease';
        observer.observe(el);
    });
    
    // Add pulse animation to important cards
    const currentBalanceCard = document.querySelector('.dashboard-item');
    if (currentBalanceCard) {
        currentBalanceCard.classList.add('pulse-animation');
    }
    
    // Enhance icons with gradient backgrounds
    const icons = document.querySelectorAll('.dashboard-item .icon');
    icons.forEach(icon => {
        icon.style.background = 'rgba(255, 255, 255, 0.2)';
        icon.style.backdropFilter = 'blur(10px)';
    });
    
    // Add hover effects to cards
    dashboardItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 15px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(76, 111, 255, 0.5)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '';
        });
    });
    
    // Format currency numbers with animation
    const animateNumber = (element, start, end, duration) => {
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 16);
    };
    
    // Apply number animation to amounts
    document.querySelectorAll('.ammount').forEach(el => {
        const text = el.textContent.trim();
        const match = text.match(/[\d,]+/);
        if (match) {
            const number = parseInt(match[0].replace(/,/g, ''));
            if (!isNaN(number) && number > 0) {
                const suffix = text.replace(match[0], '').trim();
                el.textContent = '0 ' + suffix;
                setTimeout(() => {
                    let formatted = Math.floor(number).toLocaleString();
                    el.textContent = formatted + ' ' + suffix;
                }, 100);
            }
        }
    });
    
    // Add modern tooltip to icons
    const iconElements = document.querySelectorAll('.dashboard-item .icon i');
    iconElements.forEach(icon => {
        icon.style.cursor = 'pointer';
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'rotate(360deg) scale(1.2)';
            this.style.transition = 'all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'rotate(0deg) scale(1)';
        });
    });
    
    // Create floating particles effect (optional - for extra futuristic feel)
    function createParticles() {
        const container = document.querySelector('.user-dashboard');
        if (!container) return;
        
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = Math.random() * 3 + 'px';
            particle.style.height = particle.style.width;
            particle.style.background = 'rgba(76, 111, 255, 0.3)';
            particle.style.borderRadius = '50%';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animation = `float ${Math.random() * 10 + 5}s ease-in-out infinite`;
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '-1';
            container.appendChild(particle);
        }
    }
    
    // Add float animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            50% {
                opacity: 0.5;
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            75% {
                transform: translateY(20px) translateX(-10px);
            }
        }
    `;
    document.head.appendChild(style);
    
    // Uncomment to enable particles (may affect performance on low-end devices)
    // createParticles();
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Progress bar animation
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
            bar.style.transition = 'width 1.5s cubic-bezier(0.4, 0, 0.2, 1)';
        }, 200);
    });
    
    // Add ripple effect to buttons
    document.querySelectorAll('.btn, .action-btn, .nav-item').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.5)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Add ripple animation
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(rippleStyle);
    
    console.log('✨ Modern Finance Dashboard Initialized');
});

// Copy to clipboard with modern animation
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalHTML = button.innerHTML;
        const originalBg = button.style.background;
        
        button.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        button.style.background = 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)';
        button.style.transform = 'scale(1.05)';
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.style.background = originalBg;
            button.style.transform = 'scale(1)';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Mobile bottom nav active state
function setActiveNavItem() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.bottom-nav-item').forEach(item => {
        item.classList.remove('active');
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
        }
    });
}

// Initialize on mobile
if (window.innerWidth <= 768) {
    setActiveNavItem();
}
</script>
