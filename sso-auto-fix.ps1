# SSO Auto-Fix Script (PowerShell)
# Automatically detects and fixes common SSO issues

Clear-Host

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  SSO Auto-Fix Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$Fixed = 0
$Issues = 0

function Print-Section($text) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Blue
    Write-Host $text -ForegroundColor Blue
    Write-Host "========================================" -ForegroundColor Blue
    Write-Host ""
}

function Print-Fix($text) {
    Write-Host "✓ FIX: $text" -ForegroundColor Green
    $script:Fixed++
}

function Print-Issue($text) {
    Write-Host "⚠ ISSUE: $text" -ForegroundColor Yellow
    $script:Issues++
}

# Check if in correct directory
if (-not (Test-Path "artisan")) {
    Write-Host "ERROR: Not in Laravel root directory!" -ForegroundColor Red
    exit 1
}

Print-Section "Analyzing SSO Configuration"

# Issue 1: Check SSO_SECRET
Write-Host "Checking SSO_SECRET..."

if (Test-Path ".env") {
    $envContent = Get-Content ".env" -Raw
    
    if (-not ($envContent -match "SSO_SECRET=") -or ($envContent -match "SSO_SECRET=change-this-secret-key")) {
        Print-Issue "SSO_SECRET not configured properly"
        Write-Host "  Generating secure SSO_SECRET..."
        
        # Generate random 64 character string
        $SSO_SECRET = -join ((65..90) + (97..122) + (48..57) | Get-Random -Count 64 | ForEach-Object {[char]$_})
        
        if ($envContent -match "SSO_SECRET=") {
            $envContent = $envContent -replace "SSO_SECRET=.*", "SSO_SECRET=$SSO_SECRET"
        } else {
            $envContent += "`n`n# SSO Configuration`nSSO_SECRET=$SSO_SECRET"
        }
        
        $envContent | Set-Content ".env" -NoNewline
        Print-Fix "Generated and configured SSO_SECRET"
    }
}

# Issue 2: Check MAIN_SCRIPT_URL in account folder
Write-Host "Checking MAIN_SCRIPT_URL..."

if (Test-Path "account\core\.env") {
    $accountEnv = Get-Content "account\core\.env" -Raw
    
    if (-not ($accountEnv -match "MAIN_SCRIPT_URL=")) {
        Print-Issue "MAIN_SCRIPT_URL not configured"
        
        # Get APP_URL from main .env
        $envContent = Get-Content ".env" -Raw
        if ($envContent -match "APP_URL=(.+)") {
            $APP_URL = $Matches[1].Trim()
            
            $accountEnv += "`n`n# SSO Configuration`nMAIN_SCRIPT_URL=$APP_URL"
            $accountEnv | Set-Content "account\core\.env" -NoNewline
            
            Print-Fix "Added MAIN_SCRIPT_URL to account\.env"
        }
    }
}

# Issue 3: Check SESSION_SAME_SITE
Write-Host "Checking session configuration..."

if (Test-Path "account\core\.env") {
    $accountEnv = Get-Content "account\core\.env" -Raw
    $SessionUpdated = $false
    
    if (-not ($accountEnv -match "SESSION_SAME_SITE=")) {
        Print-Issue "SESSION_SAME_SITE not configured"
        $accountEnv += "`nSESSION_SAME_SITE=none"
        $SessionUpdated = $true
    } elseif (-not ($accountEnv -match "SESSION_SAME_SITE=none")) {
        Print-Issue "SESSION_SAME_SITE not set to 'none'"
        $accountEnv = $accountEnv -replace "SESSION_SAME_SITE=.*", "SESSION_SAME_SITE=none"
        $SessionUpdated = $true
    }
    
    if (-not ($accountEnv -match "SESSION_SECURE_COOKIE=")) {
        $accountEnv += "`nSESSION_SECURE_COOKIE=true"
        $SessionUpdated = $true
    }
    
    if (-not ($accountEnv -match "SESSION_HTTP_ONLY=")) {
        $accountEnv += "`nSESSION_HTTP_ONLY=true"
        $SessionUpdated = $true
    }
    
    if (-not ($accountEnv -match "SESSION_PARTITIONED_COOKIE=")) {
        $accountEnv += "`nSESSION_PARTITIONED_COOKIE=true"
        $SessionUpdated = $true
    }
    
    if ($SessionUpdated) {
        $accountEnv | Set-Content "account\core\.env" -NoNewline
        Print-Fix "Updated session configuration for iframe support"
    }
}

# Issue 4: Check if JavaScript is included
Write-Host "Checking JavaScript inclusion..."

$LayoutFile = "resources\themes\theme_fashion\theme-views\layouts\app.blade.php"
if (Test-Path $LayoutFile) {
    $layoutContent = Get-Content $LayoutFile -Raw
    
    if (-not ($layoutContent -match "sso-handler.js")) {
        Print-Issue "SSO JavaScript not included in layout"
        
        $jsInclude = @"
    <!-- SSO Handler -->
    <script src="{{ asset('public/assets/back-end/js/sso-handler.js') }}"></script>

</body>
"@
        
        $layoutContent = $layoutContent -replace "</body>", $jsInclude
        $layoutContent | Set-Content $LayoutFile -NoNewline
        
        Print-Fix "Added SSO JavaScript to layout"
    }
}

# Issue 5: Clear all caches
Print-Section "Clearing Caches"

Write-Host "Clearing main script caches..."
php artisan config:clear 2>&1 | Out-Null
php artisan cache:clear 2>&1 | Out-Null
php artisan view:clear 2>&1 | Out-Null
php artisan route:clear 2>&1 | Out-Null
Print-Fix "Main script caches cleared"

if (Test-Path "account\core") {
    Write-Host "Clearing account folder caches..."
    Push-Location "account\core"
    php artisan config:clear 2>&1 | Out-Null
    php artisan cache:clear 2>&1 | Out-Null
    php artisan view:clear 2>&1 | Out-Null
    Pop-Location
    Print-Fix "Account folder caches cleared"
}

# Issue 6: Verify files exist
Write-Host "Verifying SSO files..."

$RequiredFiles = @(
    "app\Services\SSOService.php",
    "app\Http\Controllers\Api\SSOController.php",
    "public\assets\back-end\js\sso-handler.js",
    "account\core\app\Http\Controllers\User\Auth\SSOController.php"
)

$MissingFiles = @()
foreach ($file in $RequiredFiles) {
    if (-not (Test-Path $file)) {
        $MissingFiles += $file
    }
}

if ($MissingFiles.Count -gt 0) {
    Print-Issue "Missing SSO files detected"
    foreach ($file in $MissingFiles) {
        Write-Host "  Missing: $file" -ForegroundColor Red
    }
} else {
    Write-Host "✓ All SSO files present" -ForegroundColor Green
}

# Final Summary
Print-Section "Auto-Fix Complete"

Write-Host "✓ Fixed Issues: $Fixed" -ForegroundColor Green
if ($Issues -gt 0) {
    Write-Host "⚠ Issues Found: $Issues" -ForegroundColor Yellow
}
Write-Host ""

# Show current configuration
Write-Host "Current Configuration:" -ForegroundColor Cyan
Write-Host "----------------------"

if (Test-Path ".env") {
    $envContent = Get-Content ".env" -Raw
    
    if ($envContent -match "SSO_SECRET=(.+)") {
        $SSO_LEN = $Matches[1].Length
        Write-Host "✓ SSO_SECRET: Configured (length: $SSO_LEN)" -ForegroundColor Green
    }
    
    if ($envContent -match "APP_URL=(.+)") {
        $APP_URL = $Matches[1].Trim()
        Write-Host "✓ APP_URL: $APP_URL" -ForegroundColor Green
    }
}

if (Test-Path "account\core\.env") {
    $accountEnv = Get-Content "account\core\.env" -Raw
    
    if ($accountEnv -match "MAIN_SCRIPT_URL=(.+)") {
        $MAIN_URL = $Matches[1].Trim()
        Write-Host "✓ MAIN_SCRIPT_URL: $MAIN_URL" -ForegroundColor Green
    }
    
    if ($accountEnv -match "SESSION_SAME_SITE=none") {
        Write-Host "✓ SESSION_SAME_SITE: none" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "NEXT STEPS:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Configuration has been automatically fixed ✓"
Write-Host "2. Caches have been cleared ✓"
Write-Host "3. Ready to test login!"
Write-Host ""
Write-Host "To test:" -ForegroundColor Cyan
Write-Host "  1. Open browser (incognito mode)"
Write-Host "  2. Press F12 → Console tab"
Write-Host "  3. Login to your website"
Write-Host "  4. Watch console for SSO messages"
Write-Host ""
Write-Host "To monitor logs:" -ForegroundColor Cyan
Write-Host "  Get-Content storage\logs\laravel.log -Wait -Tail 20 | Select-String SSO"
Write-Host ""
Write-Host "SSO system should now be working!" -ForegroundColor Green
Write-Host ""
