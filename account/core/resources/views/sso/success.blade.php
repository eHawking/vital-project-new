<!DOCTYPE html>
<html>
<head>
    <title>SSO Login Successful</title>
</head>
<body>
    <p>SSO Authentication Successful</p>
    <script>
        // Send message to parent window that SSO login completed
        if (window.parent) {
            window.parent.postMessage({
                type: 'sso_login_complete',
                success: true,
                user_id: {{ $user->id ?? 'null' }}
            }, '*');
        }
        console.log('SSO login successful for user: {{ $user->username ?? "unknown" }}');
    </script>
</body>
</html>
