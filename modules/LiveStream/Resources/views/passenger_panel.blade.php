@extends('app')
@section('title', 'Painel do Passageiro - Voo ' . $pirep->ident)

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap');

    .live-dashboard-wrapper {
        font-family: 'Outfit', sans-serif;
        background: linear-gradient(135deg, #0f172a, #1e293b, #0f172a);
        color: #f8fafc;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        position: relative;
        overflow: hidden;
    }

    /* Ambient background glows */
    .live-dashboard-wrapper::before,
    .live-dashboard-wrapper::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        z-index: 0;
        pointer-events: none;
    }
    .live-dashboard-wrapper::before {
        width: 300px;
        height: 300px;
        background: rgba(56, 189, 248, 0.15); /* light blue */
        top: -100px;
        left: -100px;
    }
    .live-dashboard-wrapper::after {
        width: 400px;
        height: 400px;
        background: rgba(99, 102, 241, 0.15); /* indigo */
        bottom: -150px;
        right: -100px;
    }

    .live-dashboard-content {
        position: relative;
        z-index: 1;
    }

    .glass-panel {
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .header-title {
        font-weight: 800;
        font-size: 2rem;
        letter-spacing: -0.5px;
        background: linear-gradient(to right, #38bdf8, #818cf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        margin-bottom: 5px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        vertical-align: middle;
        margin-left: 15px;
        margin-top: -8px;
    }

    .status-live {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
    }

    .status-live::before {
        content: '';
        width: 8px;
        height: 8px;
        background-color: #34d399;
        border-radius: 50%;
        box-shadow: 0 0 8px #34d399;
        animation: pulse-dot 1.5s infinite alternate;
    }

    .status-offline {
        background: rgba(100, 116, 139, 0.15);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    @keyframes pulse-dot {
        0% { transform: scale(0.9); opacity: 0.8; }
        100% { transform: scale(1.2); opacity: 1; }
    }

    .pilot-info {
        font-size: 1.1rem;
        color: #cbd5e1;
        font-weight: 300;
    }

    .stream-wrapper {
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .stream-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .stream-offline-screen {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #0f172a;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #64748b;
    }
    
    .stream-offline-screen i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .telemetry-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .telemetry-box {
        padding: 20px 15px;
        text-align: center;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
    }

    .telemetry-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        border-color: rgba(56, 189, 248, 0.3);
    }

    .telemetry-box .icon {
        font-size: 1.2rem;
        color: #818cf8;
        margin-bottom: 8px;
    }

    .telemetry-value {
        font-size: 2rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1;
        margin-bottom: 5px;
        font-variant-numeric: tabular-nums;
        text-shadow: 0 0 10px rgba(248, 250, 252, 0.2);
    }

    .telemetry-label {
        font-size: 0.8rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
    }

    .service-menu {
        padding: 25px;
        margin-top: 20px;
    }

    .service-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding-bottom: 15px;
    }

    .service-header i {
        font-size: 1.5rem;
        color: #fbbf24;
    }

    .service-header h4 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .service-desc {
        font-size: 0.9rem;
        color: #94a3b8;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .service-tier {
        font-size: 0.85rem;
        color: #38bdf8;
        background: rgba(56, 189, 248, 0.1);
        padding: 5px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .btn-action {
        width: 100%;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        border: none;
        color: white;
        padding: 14px;
        border-radius: 12px;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-action:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
    }

    .btn-action:disabled {
        background: #475569;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .btn-action.btn-coffee { background: linear-gradient(135deg, #d97706, #b45309); box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3); }
    .btn-action.btn-coffee:hover:not(:disabled) { box-shadow: 0 8px 25px rgba(217, 119, 6, 0.5); }
    
    .btn-action.btn-dinner { background: linear-gradient(135deg, #e11d48, #be123c); box-shadow: 0 4px 15px rgba(225, 29, 72, 0.3); }
    .btn-action.btn-dinner:hover:not(:disabled) { box-shadow: 0 8px 25px rgba(225, 29, 72, 0.5); }

    .interaction-alert {
        margin-top: 15px;
        border-radius: 10px;
        padding: 12px;
        font-size: 0.9rem;
        display: none;
        animation: slide-up 0.3s ease-out;
        border: 1px solid transparent;
    }
    
    .interaction-alert.success {
        background: rgba(16, 185, 129, 0.1);
        color: #34d399;
        border-color: rgba(16, 185, 129, 0.2);
    }
    
    .interaction-alert.error {
        background: rgba(244, 63, 94, 0.1);
        color: #fb7185;
        border-color: rgba(244, 63, 94, 0.2);
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Update pulse animation for numbers */
    .val-updated {
        animation: glow-text 1s ease-out;
    }
    @keyframes glow-text {
        0% { text-shadow: 0 0 20px #38bdf8, 0 0 30px #38bdf8; color: #fff; }
        100% { text-shadow: 0 0 10px rgba(248, 250, 252, 0.2); color: #f8fafc; }
    }
</style>

<div class="container mt-5 mb-5">
    <div class="live-dashboard-wrapper">
        <div class="live-dashboard-content">
            <div class="row">
                <!-- Left Column: Video & Title -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div>
                        <h1 class="header-title">Voo {{ $pirep->ident }}</h1>
                        @if($isActive)
                            <div class="status-badge status-live">Live Agora</div>
                        @else
                            <div class="status-badge status-offline"><i class="fas fa-history"></i> Finalizado</div>
                        @endif
                    </div>
                    <div class="pilot-info mt-1 mb-4">
                        Comandante: <strong>{{ $user->name }}</strong>
                    </div>

                    <div class="stream-wrapper">
                        @if(optional($streamProfile)->is_live && optional($streamProfile)->current_stream_url)
                            <iframe src="{{ $streamProfile->current_stream_url }}&parent={{ request()->getHost() }}" allowfullscreen></iframe>
                        @else
                            <div class="stream-offline-screen">
                                <i class="fas fa-video-slash"></i>
                                <h3>Transmissão Offline</h3>
                                <p>O piloto não está a transmitir vídeo neste momento.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Telemetry & Actions -->
                <div class="col-lg-4">
                    <div class="telemetry-grid">
                        <div class="glass-panel telemetry-box">
                            <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                            <div class="telemetry-value" id="alt-val">{{ $pirep->position ? $pirep->position->altitude : 0 }}</div>
                            <div class="telemetry-label">Altitude (ft)</div>
                        </div>
                        <div class="glass-panel telemetry-box">
                            <div class="icon"><i class="fas fa-wind"></i></div>
                            <div class="telemetry-value" id="gs-val">{{ $pirep->position ? $pirep->position->gs : 0 }}</div>
                            <div class="telemetry-label">Velocidade (kts)</div>
                        </div>
                        <div class="glass-panel telemetry-box">
                            <div class="icon"><i class="fas fa-compass"></i></div>
                            <div class="telemetry-value" id="hdg-val">{{ $pirep->position ? $pirep->position->heading : 0 }}</div>
                            <div class="telemetry-label">Proa (º)</div>
                        </div>
                        <div class="glass-panel telemetry-box">
                            <div class="icon"><i class="fas fa-route"></i></div>
                            <div class="telemetry-value" id="dist-val">{{ $pirep->distance }}</div>
                            <div class="telemetry-label">Distância (nm)</div>
                        </div>
                    </div>

                    <div class="glass-panel service-menu">
                        <div class="service-header">
                            <i class="fas fa-concierge-bell"></i>
                            <h4>Serviço de Bordo</h4>
                        </div>
                        
                        <div class="service-desc">
                            Interaja com a tripulação enviando um mimo ao comandante. Acumule pontos de satisfação e desfrute da viagem.
                        </div>

                        @if($menuType == 'short')
                            <div class="service-tier"><i class="fas fa-plane"></i> Rota Curta</div>
                            <button class="btn-action btn-coffee" onclick="sendInteraction('cafe')">
                                <i class="fas fa-coffee"></i> Servir Café
                            </button>
                        @elseif($menuType == 'medium')
                            <div class="service-tier"><i class="fas fa-plane"></i> Rota Média</div>
                            <button class="btn-action" onclick="sendInteraction('sanduiche')">
                                <i class="fas fa-hamburger"></i> Enviar Sanduíche
                            </button>
                        @else
                            <div class="service-tier"><i class="fas fa-plane"></i> Rota Longa</div>
                            <button class="btn-action btn-dinner" onclick="sendInteraction('jantar')">
                                <i class="fas fa-utensils"></i> Servir Jantar Quente
                            </button>
                        @endif

                        <div id="interaction-msg" class="interaction-alert"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const pirepId = '{{ $pirep->id }}';
    const isActive = {{ $isActive ? 'true' : 'false' }};

    function updateVal(id, newVal) {
        const el = document.getElementById(id);
        if (el && el.textContent != newVal) {
            el.textContent = newVal;
            el.classList.remove('val-updated');
            void el.offsetWidth; // reflow to restart animation
            el.classList.add('val-updated');
        }
    }

    function fetchTelemetry() {
        if (!isActive) return;
        fetch('/api/pireps/' + pirepId + '/acars/position')
            .then(r => r.json())
            .then(data => {
                if (data && data.data && data.data.length > 0) {
                    const pos = data.data[data.data.length - 1];
                    updateVal('alt-val', Math.round(pos.altitude ?? 0));
                    updateVal('gs-val',  Math.round(pos.gs ?? 0));
                    updateVal('hdg-val', Math.round(pos.heading ?? 0));
                }
            })
            .catch(() => {});
    }

    if (isActive) {
        setInterval(fetchTelemetry, 10000);
        fetchTelemetry();
    }

    function sendInteraction(type) {
        const btn = document.querySelector('.btn-action');
        if (!btn || btn.disabled) return;
        btn.disabled = true;

        const msgEl = document.getElementById('interaction-msg');

        fetch('{{ route('livestream.passenger.interact', $pirep->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ type: type })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                msgEl.className = 'interaction-alert success';
                msgEl.textContent = res.message;
                msgEl.style.display = 'block';
            } else {
                throw res;
            }
        })
        .catch(err => {
            const msg = (err && err.error) ? err.error : 'Erro ao enviar interação.';
            msgEl.className = 'interaction-alert error';
            msgEl.textContent = msg;
            msgEl.style.display = 'block';
            setTimeout(() => { btn.disabled = false; }, 15 * 60 * 1000);
        });
    }
</script>
@endsection
