<div class="gtx-login-outer">
<style>
    /* ── Override Filament's simple-page constraints ── */
    .fi-simple-layout  { background: #f1f5f9 !important; padding: 0 !important; }
    .fi-simple-main-ctn{ padding: 0 !important; display: flex !important; align-items: stretch !important; min-height: 100vh !important; }
    .fi-simple-main    { max-width: 100% !important; width: 100% !important; padding: 0 !important; margin: 0 !important; }

    /* ── Wrapper ── */
    .gtx-login-outer {
        width: 100%;
        min-height: 100vh;
    }
    .gtx-login-wrapper {
        display: flex;
        min-height: 100vh;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* ══════════════════════════════════
       LEFT BRANDING PANEL
    ══════════════════════════════════ */
    .gtx-brand-panel {
        display: none;
        width: 52%;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        background: linear-gradient(155deg, #0f0c29 0%, #302b63 45%, #24243e 100%);
    }
    @media (min-width: 1024px) { .gtx-brand-panel { display: flex; } }

    /* Woven-fabric texture */
    .gtx-brand-panel::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            repeating-linear-gradient( 45deg, rgba(255,255,255,.025) 0, rgba(255,255,255,.025) 1px, transparent 1px, transparent 12px),
            repeating-linear-gradient(-45deg, rgba(255,255,255,.025) 0, rgba(255,255,255,.025) 1px, transparent 1px, transparent 12px);
        pointer-events: none;
    }
    .gtx-glow-top {
        position: absolute; top: -160px; right: -160px;
        width: 520px; height: 520px; border-radius: 50%;
        background: radial-gradient(circle, rgba(99,102,241,.35) 0%, transparent 70%);
        pointer-events: none;
    }
    .gtx-glow-bottom {
        position: absolute; bottom: -140px; left: -80px;
        width: 420px; height: 420px; border-radius: 50%;
        background: radial-gradient(circle, rgba(14,165,233,.25) 0%, transparent 70%);
        pointer-events: none;
    }
    .gtx-brand-content {
        position: relative; z-index: 1;
        display: flex; flex-direction: column; justify-content: space-between;
        padding: 3rem 3.5rem; width: 100%; min-height: 100vh;
    }

    /* Logo row */
    .gtx-brand-logo-row { display: flex; align-items: center; gap: 14px; }
    .gtx-brand-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: rgba(255,255,255,.12); backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.18);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .gtx-brand-name    { font-size: 18px; font-weight: 700; color: white; margin: 0; line-height: 1.15; }
    .gtx-brand-tagline { font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,.45); margin: 0; }

    /* Hero */
    .gtx-brand-hero h2 {
        color: white; font-size: 38px; font-weight: 800;
        line-height: 1.18; letter-spacing: -0.6px; margin: 0 0 1.1rem 0;
    }
    .gtx-brand-hero h2 span {
        display: block;
        background: linear-gradient(90deg, #818cf8, #38bdf8);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .gtx-brand-hero p {
        color: rgba(255,255,255,.6); font-size: 15px;
        line-height: 1.65; margin: 0 0 2.5rem 0; max-width: 380px;
    }
    .gtx-feature {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 1.1rem; color: rgba(255,255,255,.82); font-size: 14.5px;
    }
    .gtx-feature-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    /* Footer */
    .gtx-brand-footer { padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,.1); }
    .gtx-brand-footer p { color: rgba(255,255,255,.3); font-size: 12px; margin: 0; }

    /* ══════════════════════════════════
       RIGHT FORM PANEL
    ══════════════════════════════════ */
    .gtx-form-panel {
        flex: 1;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        padding: 2.5rem 1.5rem; background: #f1f5f9; min-height: 100vh;
    }
    .gtx-form-container { width: 100%; max-width: 440px; }

    .gtx-mobile-logo {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0;
    }
    @media (min-width: 1024px) { .gtx-mobile-logo { display: none; } }

    /* Card */
    .gtx-form-card {
        background: white; border-radius: 20px;
        padding: 2.25rem 2.25rem 2rem;
        box-shadow: 0 4px 32px rgba(15,23,42,.08), 0 1px 3px rgba(15,23,42,.05);
    }
    .gtx-form-heading { text-align: center; margin-bottom: 1.75rem; }
    .gtx-form-heading h2 { font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.4px; margin: 0 0 5px 0; }
    .gtx-form-heading p  { font-size: 13.5px; color: #64748b; margin: 0; }

    /* Strip Filament section chrome inside our white card */
    .gtx-form-card .fi-section          { background: transparent !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
    .gtx-form-card .fi-section-content  { padding: 0 !important; }
    .gtx-form-card .fi-section-header   { display: none !important; }

    /* Page footer */
    .gtx-footer { margin-top: 1.5rem; text-align: center; }
    .gtx-footer p { font-size: 12px; color: #94a3b8; margin: 0; }
    .gtx-footer strong { color: #64748b; }
</style>

<div class="gtx-login-wrapper">

    {{-- ════ LEFT BRANDING PANEL ════ --}}
    <div class="gtx-brand-panel">
        <div class="gtx-glow-top"></div>
        <div class="gtx-glow-bottom"></div>

        <div class="gtx-brand-content">
            {{-- Logo --}}
            <div class="gtx-brand-logo-row">
                <div class="gtx-brand-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2"  y="2"  width="4" height="4" rx="1" fill="white" opacity=".9"/>
                        <rect x="10" y="2"  width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="18" y="2"  width="4" height="4" rx="1" fill="white" opacity=".9"/>
                        <rect x="2"  y="10" width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="10" y="10" width="4" height="4" rx="1" fill="white" opacity=".9"/>
                        <rect x="18" y="10" width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="2"  y="18" width="4" height="4" rx="1" fill="white" opacity=".9"/>
                        <rect x="10" y="18" width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="18" y="18" width="4" height="4" rx="1" fill="white" opacity=".9"/>
                    </svg>
                </div>
                <div>
                    <p class="gtx-brand-name">Gurudev Textiles</p>
                    <p class="gtx-brand-tagline">Enterprise ERP</p>
                </div>
            </div>

            {{-- Hero --}}
            <div class="gtx-brand-hero">
                <h2>
                    Manage Your Business
                    <span>with Confidence</span>
                </h2>
                <p>
                    A complete ERP platform for textile manufacturers, wholesalers, and retailers.
                    Track stock, manage orders, and generate reports — all in one place.
                </p>

                <div class="gtx-feature">
                    <div class="gtx-feature-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="2"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <span>Smart inventory &amp; stock management</span>
                </div>

                <div class="gtx-feature">
                    <div class="gtx-feature-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <span>Automated bill &amp; challan generation</span>
                </div>

                <div class="gtx-feature">
                    <div class="gtx-feature-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6"  y1="20" x2="6"  y2="14"/>
                        </svg>
                    </div>
                    <span>Real-time sales analytics &amp; reports</span>
                </div>

                <div class="gtx-feature">
                    <div class="gtx-feature-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                            <path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <span>Multi-user with role-based access</span>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="gtx-brand-footer">
                <p>© {{ date('Y') }} Gurudev Textiles. All rights reserved.</p>
            </div>
        </div>
    </div>

    {{-- ════ RIGHT FORM PANEL ════ --}}
    <div class="gtx-form-panel">
        <div class="gtx-form-container">

            {{-- Mobile-only logo --}}
            <div class="gtx-mobile-logo">
                <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#312e81,#1d4ed8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2"  y="2"  width="4" height="4" rx="1" fill="white"/>
                        <rect x="10" y="2"  width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="18" y="2"  width="4" height="4" rx="1" fill="white"/>
                        <rect x="2"  y="10" width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="10" y="10" width="4" height="4" rx="1" fill="white"/>
                        <rect x="18" y="10" width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="2"  y="18" width="4" height="4" rx="1" fill="white"/>
                        <rect x="10" y="18" width="4" height="4" rx="1" fill="white" opacity=".7"/>
                        <rect x="18" y="18" width="4" height="4" rx="1" fill="white"/>
                    </svg>
                </div>
                <p style="font-size:16px;font-weight:700;color:#1e293b;margin:0;">Gurudev Textiles ERP</p>
            </div>

            {{-- Google auth error --}}
            @if(request()->query('google_error'))
            <div style="margin-bottom:1rem;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;display:flex;align-items:flex-start;gap:10px;">
                <svg style="flex-shrink:0;margin-top:1px;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span style="font-size:13.5px;color:#b91c1c;line-height:1.5;">{{ request()->query('google_error') }}</span>
            </div>
            @endif

            {{-- White card --}}
            <div class="gtx-form-card">
                <div class="gtx-form-heading">
                    <h2>Welcome back</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                {{-- Filament form: Google button + email/password fields --}}
                {{ $this->content }}
            </div>

            {{-- Footer --}}
            <div class="gtx-footer">
                <p>Powered by <strong>Gurudev Textiles ERP</strong> &nbsp;·&nbsp; v1.0</p>
            </div>
        </div>
    </div>

</div>

<x-filament-actions::modals />
</div>
