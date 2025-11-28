{{-- Reverse SSO Handler - Auto-login to main script after signup --}}
<script>
console.log('Reverse SSO Blade Include Loaded');
console.log('Session has reverse_sso_url (flash):', {{ session()->has('reverse_sso_url') ? 'true' : 'false' }});
console.log('Session has reverse_sso_url_backup:', {{ session()->has('reverse_sso_url_backup') ? 'true' : 'false' }});
</script>

{{-- Inline the JavaScript directly since asset path is complex in account folder --}}
<script>
/**
 * Reverse SSO Handler - Auto-login to main script after account folder signup/login
 */
(function() {
    'use strict';
    
    console.log('Reverse SSO Handler Loaded');
    
    // Check if there's a reverse SSO URL to process
    function checkReverseSSO() {
        // Check session storage for reverse SSO URL
        const reverseSsoUrl = sessionStorage.getItem('reverse_sso_url');
        
        if (reverseSsoUrl) {
            console.log('Reverse SSO: Found URL in storage:', reverseSsoUrl);
            sessionStorage.removeItem('reverse_sso_url');
            
            // Create hidden iframe to trigger main script login
            triggerMainScriptLogin(reverseSsoUrl);
        } else {
            console.log('Reverse SSO: No URL in storage');
        }
    }
    
    // Trigger main script login via iframe
    function triggerMainScriptLogin(ssoUrl) {
        console.log('Reverse SSO: Creating iframe for main script login');
        
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = ssoUrl;
        iframe.id = 'reverse-sso-iframe-' + Date.now();
        
        let loaded = false;
        
        iframe.onload = function() {
            if (!loaded) {
                loaded = true;
                console.log('Reverse SSO: Main script login completed');
                
                // Remove iframe after SSO completes
                setTimeout(function() {
                    if (iframe.parentNode) {
                        console.log('Reverse SSO: Removing iframe');
                        iframe.parentNode.removeChild(iframe);
                    }
                }, 2000);
            }
        };
        
        iframe.onerror = function(error) {
            console.error('Reverse SSO: Iframe error', error);
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        };
        
        // Timeout fallback
        setTimeout(function() {
            if (iframe.parentNode && !loaded) {
                console.warn('Reverse SSO: Iframe timeout - removing');
                iframe.parentNode.removeChild(iframe);
            }
        }, 10000);
        
        document.body.appendChild(iframe);
        console.log('Reverse SSO: Iframe appended to body');
    }
    
    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkReverseSSO);
    } else {
        checkReverseSSO();
    }
    
})();
</script>

@php
    $ssoUrl = session('reverse_sso_url') ?? session('reverse_sso_url_backup');
@endphp

@if($ssoUrl)
<script>
    // Store reverse SSO URL in sessionStorage for JavaScript to handle
    const reverseSsoUrl = '{{ $ssoUrl }}';
    console.log('Reverse SSO URL from session:', reverseSsoUrl);
    sessionStorage.setItem('reverse_sso_url', reverseSsoUrl);
    console.log('Reverse SSO URL set in sessionStorage');
    
    // Clear backup session
    @if(session()->has('reverse_sso_url_backup'))
        {{ session()->forget('reverse_sso_url_backup') }};
    @endif
</script>
@else
<script>
    console.log('No reverse SSO URL found in session');
</script>
@endif
