<style>
    :root {
        --bg-dark: #0f172a;
        --card-bg: rgba(30, 41, 59, 0.7);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --accent-blue: #3b82f6;
        --accent-purple: #8b5cf6;
        --grad-primary: linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
        --input-bg: rgba(15, 23, 42, 0.6);
    }

    body {
        background-color: var(--bg-dark) !important;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.15) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 40%) !important;
        color: var(--text-primary) !important;
        font-family: 'Inter', sans-serif;
    }

    .account-section {
        background: transparent !important;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .account-form-wrapper {
        background: var(--card-bg) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-left: 1px solid var(--glass-border) !important;
        box-shadow: -10px 0 30px rgba(0,0,0,0.3) !important;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 40px;
    }

    .account-thumb {
        height: 100vh;
        background-size: cover !important;
        background-position: center !important;
        position: relative;
    }

    .account-thumb::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.4));
    }

    .account-thumb-content {
        position: relative;
        z-index: 2;
        color: #fff;
        padding: 60px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .account-thumb-content .title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .form--label {
        color: var(--text-secondary) !important;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form--control {
        background: var(--input-bg) !important;
        border: 1px solid var(--glass-border) !important;
        color: var(--text-primary) !important;
        border-radius: 12px !important;
        padding: 12px 15px !important;
        transition: all 0.3s ease;
    }

    .form--control:focus {
        border-color: var(--accent-blue) !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }

    .account--btn {
        background: var(--grad-primary) !important;
        color: white !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 14px !important;
        font-weight: 600 !important;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .account--btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4) !important;
    }

    .custom--btn {
        background: transparent !important;
        border: 1px solid var(--glass-border) !important;
        color: var(--text-primary) !important;
        border-radius: 12px !important;
        padding: 12px !important;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 10px;
    }

    .custom--btn:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: var(--text-primary) !important;
        color: #fff !important;
    }

    .logo img {
        max-height: 50px;
        margin-bottom: 30px;
        filter: brightness(0) invert(1); /* Make logo white if it's dark */
    }

    .text--dark {
        color: var(--text-secondary) !important;
    }

    .text--base {
        color: var(--accent-blue) !important;
    }

    /* Remove old shapes if they interfere */
    .shape {
        display: none !important;
    }
    
    /* Mobile fixes */
    @media (max-width: 768px) {
        .account-form-wrapper {
            padding: 20px;
            height: auto;
            min-height: 100vh;
        }
        .account-thumb {
            display: none;
        }
    }
</style>
