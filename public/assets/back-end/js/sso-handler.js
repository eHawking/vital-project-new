/**
 * SSO Handler - Manages Single Sign-On between main script and account folder
 */

(function($) {
    'use strict';
    
    console.log('SSO Handler Loaded');
    
    // Handle SSO login after successful authentication
    function handleSSOLogin(ssoUrl) {
        if (!ssoUrl) {
            console.warn('SSO: No URL provided');
            return;
        }
        
        console.log('SSO: Creating iframe for URL:', ssoUrl);
        
        // Open SSO login in hidden iframe
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = ssoUrl;
        iframe.id = 'sso-iframe-' + Date.now();
        
        let loaded = false;
        
        iframe.onload = function() {
            if (!loaded) {
                loaded = true;
                console.log('SSO: Iframe loaded successfully');
                
                // Remove iframe after SSO completes
                setTimeout(function() {
                    if (iframe.parentNode) {
                        console.log('SSO: Removing iframe');
                        iframe.parentNode.removeChild(iframe);
                    }
                }, 2000);
            }
        };
        
        iframe.onerror = function(error) {
            console.error('SSO: Iframe error', error);
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        };
        
        // Timeout fallback
        setTimeout(function() {
            if (iframe.parentNode && !loaded) {
                console.warn('SSO: Iframe timeout - removing');
                iframe.parentNode.removeChild(iframe);
            }
        }, 10000);
        
        document.body.appendChild(iframe);
        console.log('SSO: Iframe appended to body');
    }
    
    // Intercept AJAX login responses
    $(document).on('ajaxSuccess', function(event, xhr, settings) {
        console.log('AJAX Success:', settings.url);
        
        // Check if this is a login request
        if (settings.url && (settings.url.includes('/customer/auth/login') || settings.url.includes('login'))) {
            try {
                const response = xhr.responseJSON;
                
                console.log('Login Response:', response);
                
                if (response && response.status === 'success') {
                    if (response.sso_url) {
                        console.log('SSO URL received:', response.sso_url);
                        
                        // Trigger SSO login after a short delay
                        setTimeout(function() {
                            handleSSOLogin(response.sso_url);
                        }, 500);
                    } else {
                        console.warn('SSO: Login successful but no sso_url in response');
                    }
                } else {
                    console.log('SSO: Login failed or status not success');
                }
            } catch (e) {
                console.error('SSO: Error parsing response', e);
            }
        }
    });
    
    // Check for SSO login URL in session on page load
    $(document).ready(function() {
        console.log('SSO: Document ready');
        
        const ssoLoginUrl = sessionStorage.getItem('sso_login_url');
        
        if (ssoLoginUrl) {
            console.log('SSO: Found URL in storage:', ssoLoginUrl);
            sessionStorage.removeItem('sso_login_url');
            handleSSOLogin(ssoLoginUrl);
        } else {
            console.log('SSO: No URL in storage');
        }
    });
    
})(jQuery);
