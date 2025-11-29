<style>
/* Clean up inline styles here, move logic to main CSS where possible, 
   but keeping minimal structural overrides if needed */
.dashboard-user::before { display: none; }
.name { text-shadow: 0 2px 4px rgba(0,0,0,0.5); }

/* Bell Animation */
@keyframes ring {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(15deg); }
    50% { transform: rotate(-15deg); }
    75% { transform: rotate(5deg); }
    100% { transform: rotate(0deg); }
}
.bell-icon {
    display: inline-block;
    color: var(--color-danger);
    animation: ring 1s ease-in-out infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
.pulse-animation { animation: pulse 1s infinite; }

/* Sidebar Icons */
.user-dashboard-tab li a svg {
    width: 20px;
    height: 20px;
    stroke-width: 2;
    fill: none;
    stroke: currentColor;
    margin-right: 10px;
}

/* Premium Mobile Header */
.premium-mobile-header {
    background: var(--grad-primary);
    padding: 15px 20px;
    border-radius: 20px;
    box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.1);
}

.premium-mobile-header::before {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 100px; height: 100px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    pointer-events: none;
}

.header-icon-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    color: white;
    font-size: 20px;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    backdrop-filter: blur(5px);
}

.header-icon-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}
</style>

<section class="user-dashboard" style="padding-top: 20px; padding-bottom: 50px;">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-lg-3">
                <div class="premium-sidebar dashboard-sidebar">
                    
                    <div class="close-dashboard d-lg-none" onclick="togglePremiumSidebar()">
                        <i class="las la-times text-white"></i>
                    </div>
                    
                    <div class="sidebar-scroll-wrapper">
                        <div class="dashboard-user">
                            @if(auth()->user()->plan_id == 1)
                                <div style="position: relative;">
                                    <i id="checkIcon" class="fas fa-check-circle" style="font-size: 24px; position: absolute; top: -10px; right: -10px; background: white; color: blue; border-radius: 50%; z-index: 10;"></i>
                                    <div id="popup" style="display: none; position: absolute; top: -40px; right: -60px; background: white; padding: 5px 10px; border: 1px solid blue; border-radius: 6px; z-index: 20; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: black; white-space: nowrap; font-size: 12px;">
                                        Verified Paid <span class="text-uppercase fw-bold">{{ auth()->user()->dsp_username }}</span>
                                    </div>
                                </div>
                                
                                <div class="user-thumb" style="border: 4px solid #2ecc71;">
                                    <img src="/storage/app/public/profile/{{ auth()->user()->image }}" alt="profile">
                                </div>
                            @else
                                <div class="user-thumb">
                                    <img src="/storage/app/public/profile/{{ auth()->user()->image }}" alt="profile">
                                </div>
                            @endif
                            
                            <div class="user-content">
                                <small class="text-white-50 d-block mb-1">@lang('WELCOME')</small>
                                <h5 class="name text-white mb-3">{{ auth()->user()->fullname }}</h5>
                                
                                <small class="text-white-50 d-block mb-1">@lang('ACCOUNT ID')</small>
                                <div class="bg-white rounded p-1 shadow-sm d-inline-block px-3">
                                    <h5 class="m-0 text-dark fw-bold" style="transform: skew(-10deg); font-family: monospace; font-size: 1.2rem;">
                                        {{ auth()->user()->username }}
                                    </h5>
                                </div>
                            </div>
                            
                            <a class="btn btn-light btn-sm w-100 mt-3 fw-bold text-primary" href="https://dewdropskin.com">
                                <i class="las la-store me-1"></i> @lang('Back to Marketplace')
                            </a>
                        </div>

                        <ul class="user-dashboard-tab">
                            <li>
                                <a class="{{menuActive('user.home')}}" href="{{route('user.home')}}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> 
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="pulse-animation">
                                <a class="{{menuActive('user.notifications')}}" href="{{route('user.notifications')}}">
                                    <svg viewBox="0 0 24 24" class="bell-icon"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg> 
                                    @lang('LIVE Notifications')
                                </a>
                            </li>
                            <li>
                                <a class="{{menuActive('user.my.ref')}}" href="{{ route('user.my.ref') }}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg> 
                                    @lang('My Referrals')
                                </a>
                            </li>
                            <li>
                                <a class="{{menuActive('user.my.summery')}}" href="{{ route('user.my.summery') }}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> 
                                    @lang('My Summary')
                                </a>
                            </li>
                            <li>
                                <a class="{{menuActive('user.my.tree')}}" href="{{ route('user.my.tree') }}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg> 
                                    @lang('My Tree')
                                </a>
                            </li>
                            <li>
                                <a class="{{menuActive('user.shop-franchise')}}" href="{{ route('user.shops-franchises') }}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> 
                                    @lang('Shops & Franchises')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.dsp.vouchers') }}" class="{{menuActive('user.dsp.vouchers')}}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg> 
                                    @lang('DSP Vouchers')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.rewards') }}" class="{{ menuActive('user.rewards') }}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 
                                    @lang('Ranks & Rewards')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.withdraw.history') }}" class="{{menuActive('user.withdraw*')}}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 
                                    @lang('Withdraw History')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.transactions') }}" class="{{menuActive('user.transactions')}}">
                                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> 
                                    @lang('Transactions History')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <!-- Premium Mobile Header (Replaces Old Toggler) -->
                <div class="user-toggler-wrapper d-flex d-lg-none align-items-center justify-content-between mb-4 premium-mobile-header">
                    <h4 class="title m-0 text-white fw-bold">{{ __($pageTitle) }}</h4>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Theme Toggle Button -->
                        <button class="header-icon-btn theme-toggle-btn" id="dashboardThemeToggle" onclick="document.getElementById('themeSettingsToggle')?.click()">
                            <i class="las la-adjust"></i>
                        </button>
                        
                        <!-- Sidebar Toggle Button -->
                        <div class="user-toggler header-icon-btn" id="premiumSidebarToggler" onclick="togglePremiumSidebar()">
                            <i class="las la-bars"></i>
                        </div>
                    </div>
                </div>

                @yield('content')
            </div>
        </div>
    </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      // --- NEW SIDEBAR LOGIC ---
      const sidebar = document.querySelector('.premium-sidebar');
      const toggleBtn = document.getElementById('premiumSidebarToggler');
      const closeBtn = document.querySelector('.close-dashboard');
      const body = document.body;
      
      // Create or Select Overlay
      let overlay = document.querySelector('.sidebar-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.className = 'sidebar-overlay';
          document.body.appendChild(overlay);
      }

      // Function to Open Sidebar
      function openSidebar() {
          sidebar.classList.add('show-sidebar');
          overlay.classList.add('active');
          body.style.overflow = 'hidden'; // Prevent background scrolling
      }

      // Function to Close Sidebar
      function closeSidebar() {
          sidebar.classList.remove('show-sidebar');
          overlay.classList.remove('active');
          body.style.overflow = ''; // Restore background scrolling
      }

      // Toggle Event
      if (toggleBtn) {
          toggleBtn.addEventListener('click', function(e) {
              e.stopPropagation();
              if (sidebar.classList.contains('show-sidebar')) {
                  closeSidebar();
              } else {
                  openSidebar();
              }
          });
      }

      // Close Button Event
      if (closeBtn) {
          closeBtn.addEventListener('click', function(e) {
              e.stopPropagation();
              closeSidebar();
          });
      }

      // Overlay Click Event
      overlay.addEventListener('click', function(e) {
          closeSidebar();
      });

      // Swipe to Close (Optional simple implementation)
      let touchStartX = 0;
      let touchEndX = 0;
      
      document.addEventListener('touchstart', e => {
          touchStartX = e.changedTouches[0].screenX;
      }, {passive: true});

      document.addEventListener('touchend', e => {
          touchEndX = e.changedTouches[0].screenX;
          if (touchStartX - touchEndX > 100 && sidebar.classList.contains('show-sidebar')) {
              closeSidebar(); // Swipe Left to close
          }
      }, {passive: true});

      // --- TOOLTIP LOGIC ---
      const checkIcon = document.getElementById("checkIcon");
      const popup = document.getElementById("popup");
      
      if(checkIcon && popup) {
          checkIcon.addEventListener("mouseenter", () => popup.style.display = "block");
          checkIcon.addEventListener("mouseleave", () => popup.style.display = "none");
      }
  });
</script>

<style>
    /* Sidebar Toggle Styles */
    @media (max-width: 991px) {
        .premium-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            transform: translateX(-100%); /* Use transform for performance */
            width: 100%; /* Full width per request */
            max-width: 100%;
            height: 100vh;
            height: 100dvh; 
            z-index: 99999; 
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden !important; /* Disable scroll on main container */
            background: var(--bg-card, #1e293b);
            box-shadow: none;
            display: flex !important;
            flex-direction: column;
            padding-bottom: 0 !important;
            will-change: transform;
        }

        .sidebar-scroll-wrapper {
            flex: 1;
            min-height: 0; /* Crucial for flex scrolling */
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 150px; /* Extra padding to ensure last items are visible */
            width: 100%;
            padding-top: 20px;
            touch-action: pan-y; /* Allow vertical scroll, prevent horizontal */
        }

        .premium-sidebar .user-dashboard-tab {
            flex: 1;
        }

        /* Full width sidebar items on mobile */
        .premium-sidebar .user-dashboard-tab li {
            margin-bottom: 0 !important;
            width: 100%;
        }

        .premium-sidebar .user-dashboard-tab li a {
            border-radius: 0 !important;
            margin: 0 !important;
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 15px 20px !important;
        }
        
        .premium-sidebar.show-sidebar {
            transform: translateX(0) !important; /* Slide in */
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: transparent;
            z-index: 99998;
            display: none;
            -webkit-tap-highlight-color: transparent;
        }
        
        .sidebar-overlay.active {
            display: block;
        }

        /* Close button positioning */
        .close-dashboard {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            cursor: pointer;
            z-index: 100001; /* Higher than everything */
        }
    }
</style>