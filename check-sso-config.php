<?php

/**
 * SSO Configuration Checker
 * Run this from command line: php check-sso-config.php
 */

echo "========================================\n";
echo "SSO Configuration Checker\n";
echo "========================================\n\n";

// Check 1: .env file exists
echo "✓ Checking Main Script Configuration...\n";

if (!file_exists('.env')) {
    echo "  ❌ ERROR: .env file not found!\n\n";
    exit(1);
}

// Load .env
$env = file_get_contents('.env');

// Check SSO_SECRET
if (strpos($env, 'SSO_SECRET') !== false) {
    preg_match('/SSO_SECRET=(.+)/', $env, $matches);
    $secret = trim($matches[1] ?? '');
    
    if (empty($secret) || $secret === 'change-this-secret-key') {
        echo "  ❌ SSO_SECRET not configured or using default value\n";
        echo "     Add to .env: SSO_SECRET=your-random-64-character-string\n\n";
    } else {
        echo "  ✅ SSO_SECRET configured (length: " . strlen($secret) . ")\n";
    }
} else {
    echo "  ❌ SSO_SECRET not found in .env\n";
    echo "     Add to .env: SSO_SECRET=your-random-64-character-string\n\n";
}

// Check APP_URL
if (strpos($env, 'APP_URL') !== false) {
    preg_match('/APP_URL=(.+)/', $env, $matches);
    $appUrl = trim($matches[1] ?? '');
    echo "  ✅ APP_URL configured: $appUrl\n";
} else {
    echo "  ❌ APP_URL not found in .env\n\n";
}

echo "\n";

// Check 2: Account folder configuration
echo "✓ Checking Account Folder Configuration...\n";

if (!file_exists('account/core/.env')) {
    echo "  ❌ ERROR: account/core/.env file not found!\n\n";
} else {
    $accountEnv = file_get_contents('account/core/.env');
    
    // Check MAIN_SCRIPT_URL
    if (strpos($accountEnv, 'MAIN_SCRIPT_URL') !== false) {
        preg_match('/MAIN_SCRIPT_URL=(.+)/', $accountEnv, $matches);
        $mainScriptUrl = trim($matches[1] ?? '');
        echo "  ✅ MAIN_SCRIPT_URL configured: $mainScriptUrl\n";
    } else {
        echo "  ❌ MAIN_SCRIPT_URL not found\n";
        echo "     Add to account/core/.env: MAIN_SCRIPT_URL=https://dewdropskin.com\n\n";
    }
    
    // Check SESSION_SAME_SITE
    if (strpos($accountEnv, 'SESSION_SAME_SITE') !== false) {
        preg_match('/SESSION_SAME_SITE=(.+)/', $accountEnv, $matches);
        $sameSite = trim($matches[1] ?? '');
        
        if ($sameSite === 'none') {
            echo "  ✅ SESSION_SAME_SITE=none (correct for iframe)\n";
        } else {
            echo "  ⚠️  SESSION_SAME_SITE=$sameSite (should be 'none' for SSO)\n";
        }
    } else {
        echo "  ❌ SESSION_SAME_SITE not configured\n";
        echo "     Add to account/core/.env: SESSION_SAME_SITE=none\n\n";
    }
    
    // Check SESSION_SECURE_COOKIE
    if (strpos($accountEnv, 'SESSION_SECURE_COOKIE') !== false) {
        echo "  ✅ SESSION_SECURE_COOKIE configured\n";
    } else {
        echo "  ❌ SESSION_SECURE_COOKIE not configured\n";
        echo "     Add to account/core/.env: SESSION_SECURE_COOKIE=true\n\n";
    }
}

echo "\n";

// Check 3: Files exist
echo "✓ Checking Required Files...\n";

$files = [
    'app/Services/SSOService.php' => 'SSO Service',
    'app/Http/Controllers/Api/SSOController.php' => 'API Controller',
    'public/assets/back-end/js/sso-handler.js' => 'JavaScript Handler',
    'account/core/app/Http/Controllers/User/Auth/SSOController.php' => 'Account SSO Controller',
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "  ✅ $name exists\n";
    } else {
        echo "  ❌ $name missing: $file\n";
    }
}

echo "\n";

// Check 4: Layout includes JavaScript
echo "✓ Checking Layout Configuration...\n";

$layoutFile = 'resources/themes/theme_fashion/theme-views/layouts/app.blade.php';
if (file_exists($layoutFile)) {
    $layout = file_get_contents($layoutFile);
    
    if (strpos($layout, 'sso-handler.js') !== false) {
        echo "  ✅ SSO JavaScript included in layout\n";
    } else {
        echo "  ❌ SSO JavaScript NOT included in layout\n";
        echo "     Add before </body>: <script src=\"{{ asset('public/assets/back-end/js/sso-handler.js') }}\"></script>\n\n";
    }
} else {
    echo "  ❌ Layout file not found\n";
}

echo "\n";

// Check 5: Cache configuration
echo "✓ Checking Cache Configuration...\n";

if (function_exists('config')) {
    $cacheDriver = config('cache.default', 'file');
    echo "  ℹ️  Cache driver: $cacheDriver\n";
} else {
    echo "  ℹ️  Run 'php artisan config:cache' to optimize\n";
}

echo "\n========================================\n";
echo "Configuration Check Complete\n";
echo "========================================\n\n";

echo "NEXT STEPS:\n";
echo "1. Fix any ❌ errors shown above\n";
echo "2. Run: php artisan config:clear\n";
echo "3. Run: php artisan cache:clear\n";
echo "4. Test login with browser console open (F12)\n";
echo "5. Check logs: storage/logs/laravel.log\n\n";
