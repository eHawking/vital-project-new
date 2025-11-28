<div class="overlay"></div>
<!-- Preloader -->
<div id="preloader">
    <div id="loader"></div>
</div>

<!-- Header Section Starts Here -->
<header class="header">
    <div class="header-bottom">
        <div class="container-fluid">
            <div class="header-bottom-area">
                <!-- Desktop Logo -->
                <div class="logo d-none d-lg-block">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo('dark') }}" alt="logo">
                    </a>
                </div>

                <!-- Mobile Header Layout -->
                <div class="d-flex d-lg-none align-items-center justify-content-between w-100">
                    <!-- Sidebar Toggle Button -->
                    <button class="mobile-sidebar-toggle" type="button">
                        <i class="las la-bars"></i>
                    </button>

                    <!-- Page Title (Centered) -->
                    <div class="mobile-page-title">
                        <h6 class="m-0 fw-bold">{{ __($pageTitle) }}</h6>
                    </div>

                    <!-- User Profile/Icon (Right) -->
                    <a class="mobile-user-icon" href="{{ route('user.home') }}">
                        <div class="user-thumb-xs">
                            <img src="{{ auth()->check() ? getImage(getFilePath('userProfile') . '/' . auth()->user()->image, getFileSize('userProfile')) : siteLogo('dark') }}" alt="user">
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <ul class="menu d-none d-lg-flex">
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    <li><a href="{{ route('products') }}">@lang('Product')</a></li>

                    @foreach ($pages as $k => $data)
                        <li><a href="{{ route('pages', [$data->slug]) }}">{{ $data->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                    <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>

                    <li>
                        @if (gs('multi_language'))
                            @php
                                $language = App\Models\Language::all();
                                $selectLang = $language->where('code', config('app.locale'))->first();
                                $currentLang = session('lang')
                                    ? $language->where('code', session('lang'))->first()
                                    : $language->where('is_default', Status::YES)->first();
                            @endphp

                            <div class="custom--dropdown">
                                <div class="custom--dropdown__selected dropdown-list__item">
                                    <div class="thumb">
                                        <img src="{{ getImage(getFilePath('language') . '/' . $currentLang->image, getFileSize('language')) }}"
                                            alt="image">
                                    </div>
                                    <span class="text"> {{ __(@$selectLang->name) }} </span>
                                </div>
                                <ul class="dropdown-list">
                                    @foreach ($language as $item)
                                        <li class="dropdown-list__item" data-value="en">
                                            <a class="thumb" href="{{ route('lang', $item->code) }}"> <img
                                                    src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}"
                                                    alt="image">
                                                <span class="text"> {{ __($item->name) }} </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>

                    <li class="account-cart-wrapper">
                        <a class="account" href="{{ route('user.login') }}"><i class="las la-user"></i></a>
                    </li>
                </ul> 
            </div>
        </div>
    </div>
</header>

<style>
/* Mobile Header Styling */
.mobile-sidebar-toggle {
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 24px;
    padding: 0;
}

.mobile-page-title {
    flex: 1;
    text-align: center;
    color: var(--text-primary);
}

.mobile-user-icon {
    display: block;
}

.user-thumb-xs {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--color-primary);
}

.user-thumb-xs img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (max-width: 991px) {
    .header-bottom {
        padding: 10px 0;
        background: var(--bg-sidebar); /* Use premium theme var */
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-card);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.mobile-sidebar-toggle');
    const sidebar = document.querySelector('.dashboard-sidebar'); // Target your sidebar class
    const body = document.body;

    if(sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            // Logic to toggle sidebar visibility 
            // This assumes your sidebar has a class or style to show/hide it
            // You might need to add a class like 'active' or 'show' to the sidebar
            
            sidebar.classList.toggle('show-sidebar'); 
            
            // Create overlay if it doesn't exist
            let overlay = document.querySelector('.sidebar-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show-sidebar');
                    overlay.classList.remove('active');
                });
            }
            
            // Toggle overlay
            if (sidebar.classList.contains('show-sidebar')) {
                overlay.classList.add('active');
                sidebar.style.display = 'block'; // Force show if hidden
            } else {
                overlay.classList.remove('active');
            }
        });
    }
});
</script>
<style>
/* Add this to your main CSS or here for sidebar toggle */
@media (max-width: 991px) {
    .dashboard-sidebar {
        position: fixed;
        top: 0;
        left: -280px; /* Hide off-screen */
        width: 280px;
        height: 100vh;
        z-index: 9999;
        transition: all 0.3s ease;
        overflow-y: auto;
        display: block !important; /* Override display:none from previous edit */
    }
    
    .dashboard-sidebar.show-sidebar {
        left: 0;
    }
    
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        backdrop-filter: blur(3px);
    }
    
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
}
</style>
<!-- Header Section Ends Here -->
