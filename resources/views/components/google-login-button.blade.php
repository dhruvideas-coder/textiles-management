<div style="margin-bottom: 1.5rem;">
    {{-- Google Sign-In Button --}}
    <a
        href="{{ route('login.google') }}"
        style="
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            border-radius: 8px;
            border: 1.5px solid #dadce0;
            background: #ffffff;
            padding: 11px 16px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            color: #3c4043;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s, border-color 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,.08);
            letter-spacing: 0.01em;
        "
        onmouseover="this.style.background='#f8f9fa'; this.style.boxShadow='0 2px 8px rgba(66,133,244,0.15)'; this.style.borderColor='#4285f4';"
        onmouseout="this.style.background='#ffffff'; this.style.boxShadow='0 1px 2px rgba(0,0,0,.08)'; this.style.borderColor='#dadce0';"
    >
        <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span>Continue with Google</span>
    </a>

    {{-- Divider --}}
    <div style="position: relative; margin: 1.25rem 0;">
        <div style="position: absolute; inset: 0; display: flex; align-items: center;">
            <div style="width: 100%; border-top: 1px solid #e5e7eb;"></div>
        </div>
        <div style="position: relative; display: flex; justify-content: center;">
            <span style="background: white; padding: 0 12px; font-size: 12px; color: #9ca3af; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase;">or sign in with email</span>
        </div>
    </div>
</div>
