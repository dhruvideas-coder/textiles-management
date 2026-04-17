@auth
<style>
/* Advanced Premium Popup CSS */
#auto-logout-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(8px);
    z-index: 999998;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}
#auto-logout-overlay.show {
    display: flex;
    opacity: 1;
}
#auto-logout-modal {
    background: #ffffff;
    border-radius: 20px;
    padding: 35px 30px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    text-align: center;
    transform: scale(0.9) translateY(20px);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit;
}
html.dark #auto-logout-modal {
    background: #1e293b;
    color: #f8fafc;
    border: 1px solid #334155;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}
#auto-logout-overlay.show #auto-logout-modal {
    transform: scale(1) translateY(0);
}
.al-icon-wrapper {
    background: #fee2e2;
    color: #ef4444;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
}
html.dark .al-icon-wrapper {
    background: rgba(239, 68, 68, 0.15);
}
.al-icon-wrapper svg {
    width: 36px;
    height: 36px;
}
.al-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #0f172a;
}
html.dark .al-title {
    color: #f8fafc;
}
.al-text {
    font-size: 1.05rem;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 30px;
}
html.dark .al-text {
    color: #cbd5e1;
}
.al-countdown {
    font-weight: 800;
    color: #ef4444;
    font-size: 1.2rem;
}
.al-button {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    padding: 14px 24px;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
}
.al-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
}
html.dark .al-button {
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
}
</style>

<div id="auto-logout-overlay">
    <div id="auto-logout-modal">
        <div class="al-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="al-title">Session Expiring</h3>
        <p class="al-text">
            For your security, you are about to be automatically logged out due to inactivity.<br><br>
            Time remaining: <span class="al-countdown" id="auto-logout-countdown">30</span>s
        </p>
        <button id="auto-logout-btn" class="al-button">I'm still here</button>
    </div>
</div>

<script>
(function() {
    let idleTime = 0;
    let countdown = 30;
    const idleLimit = 300; // 5 minutes
    const countdownLimit = 30;
    let timerInterval = null;
    let isWarningShown = false;

    const overlay = document.getElementById('auto-logout-overlay');
    const countdownEl = document.getElementById('auto-logout-countdown');
    const btn = document.getElementById('auto-logout-btn');

    function resetTimer(isManualClick = false) {
        if (isWarningShown && !isManualClick) return;
        
        idleTime = 0;
        countdown = countdownLimit;
        
        if (isWarningShown) {
            isWarningShown = false;
            overlay.classList.remove('show');
            setTimeout(() => {
                if(!isWarningShown) overlay.style.display = 'none';
            }, 400);
        }
    }

    function handleActivity(e) {
        if (isWarningShown) {
            if (e.type === 'click' || e.type === 'touchstart' || e.type === 'keydown') {
                resetTimer(true);
            }
            return;
        }
        resetTimer();
    }

    ['mousemove', 'keydown', 'scroll', 'touchstart', 'click'].forEach(evt => {
        document.addEventListener(evt, handleActivity, true);
    });

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        resetTimer(true);
    });

    timerInterval = setInterval(() => {
        idleTime++;
        if (idleTime >= idleLimit) {
            if (!isWarningShown) {
                isWarningShown = true;
                countdown = countdownLimit;
                countdownEl.innerText = countdown;
                overlay.style.display = 'flex';
                void overlay.offsetWidth; // Trigger reflow
                overlay.classList.add('show');
            } else {
                countdown--;
                countdownEl.innerText = countdown;

                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    doLogout();
                }
            }
        }
    }, 1000);

    function doLogout() {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ filament()->getLogoutUrl() }}';
        
        let metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = metaToken.getAttribute('content');
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    }
})();
</script>
@endauth
