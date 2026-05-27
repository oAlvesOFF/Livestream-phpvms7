@extends('app')
@section('title', 'Stream Settings')

@section('css')
@parent
<style>
.ls-wrap { max-width: 760px; margin: 0 auto; padding-bottom: 40px; }

/* Hero */
.ls-hero {
  background: linear-gradient(135deg, #6441a5 0%, #2a0845 100%);
  border-radius: 16px; padding: 30px 36px; margin-bottom: 24px;
  position: relative; overflow: hidden;
}
.ls-hero::before {
  content:''; position:absolute; top:-60px; right:-60px;
  width:240px; height:240px; background:rgba(255,255,255,0.04); border-radius:50%;
}
.ls-hero-inner { position: relative; z-index: 1; }
.ls-hero h2 { color:#fff; font-weight:800; font-size:1.5rem; margin:0 0 6px; }
.ls-hero p  { color:rgba(255,255,255,0.6); font-size:0.88rem; margin:0; }

.ls-live-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(220,53,69,0.2); border:1px solid rgba(220,53,69,0.4);
  border-radius:20px; padding:3px 12px; margin-top:12px;
  font-size:0.7rem; font-weight:800; color:#ff6b7a; letter-spacing:1px;
}
.ls-live-dot { width:7px; height:7px; background:#ff4d5e; border-radius:50%; animation:pulseDot 1.4s infinite; }
.ls-offline-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12);
  border-radius:20px; padding:3px 12px; margin-top:12px;
  font-size:0.7rem; font-weight:700; color:rgba(255,255,255,0.45); letter-spacing:1px;
}
@keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:0.4;transform:scale(0.75);} }

/* Cards */
.ls-card {
  background: var(--bg-card, #1a1d21);
  border: 1px solid var(--border-color, rgba(255,255,255,0.07));
  border-radius: 14px; margin-bottom: 16px; overflow: hidden;
}
.ls-card-header {
  padding: 13px 20px;
  border-bottom: 1px solid var(--border-color, rgba(255,255,255,0.06));
  display: flex; align-items: center; gap: 10px;
}
.ls-card-header h5 { margin:0; font-size:0.9rem; font-weight:700; color: var(--text-heading, #fff); }
.ls-card-body { padding: 18px 20px; }

.ls-icon {
  width:32px; height:32px; border-radius:8px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center; font-size:14px;
}
.ic-tw { background:rgba(145,70,255,0.15); color:#9146FF; }
.ic-yt { background:rgba(255,0,0,0.12);    color:#FF0000; }
.ic-dc { background:rgba(88,101,242,0.15);  color:#5865F2; }
.ic-ob { background:rgba(0,200,255,0.1);    color:#00c8ff; }

/* Inputs */
.ls-label { display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:var(--text-muted,#8a94a6); margin-bottom:6px; }
.ls-ig { display:flex; }
.ls-pfx { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-right:none; border-radius:8px 0 0 8px; padding:9px 12px; font-size:0.8rem; color:var(--text-muted,#8a94a6); flex-shrink:0; }
.ls-inp {
  flex:1; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
  color:var(--text-body,#e9ecef); border-radius:0 8px 8px 0; padding:9px 13px; font-size:0.87rem;
  transition:border-color .2s, box-shadow .2s; width:100%;
}
.ls-inp.solo { border-radius:8px; }
.ls-inp:focus { border-color:#9146FF; box-shadow:0 0 0 3px rgba(145,70,255,.15); background:rgba(255,255,255,.07); color:#fff; outline:none; }
.ls-inp.yt:focus { border-color:#FF0000; box-shadow:0 0 0 3px rgba(255,0,0,.1); }
.ls-inp.dc:focus { border-color:#5865F2; box-shadow:0 0 0 3px rgba(88,101,242,.13); }
.ls-hint { font-size:0.73rem; color:var(--text-muted,#8a94a6); margin-top:5px; }
.ls-hint a { color:#9146FF; }

/* Twitch live preview */
.twitch-preview {
  background:rgba(145,70,255,0.07); border:1px solid rgba(145,70,255,0.22);
  border-radius:9px; padding:11px 15px; margin-top:13px;
  display:flex; align-items:center; gap:10px; font-size:0.82rem; color:rgba(255,255,255,.7);
}
.twitch-preview strong { color:#9146FF; }

/* OBS toggle */
.ls-toggle-row { display:flex; align-items:center; justify-content:space-between; }
.ls-toggle-info h6 { margin:0 0 2px; font-size:0.88rem; font-weight:700; color:var(--text-heading,#fff); }
.ls-toggle-info p  { margin:0; font-size:0.76rem; color:var(--text-muted,#8a94a6); }
.ls-sw { position:relative; width:44px; height:24px; flex-shrink:0; }
.ls-sw input { opacity:0; width:0; height:0; }
.ls-sl { position:absolute; cursor:pointer; top:0;left:0;right:0;bottom:0; background:rgba(255,255,255,0.1); border-radius:12px; transition:.3s; }
.ls-sl::before { content:''; position:absolute; width:18px; height:18px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.3s; }
input:checked + .ls-sl { background:#9146FF; }
input:checked + .ls-sl::before { transform:translateX(20px); }

/* OBS URL box */
.obs-url {
  background:rgba(0,200,255,0.05); border:1px solid rgba(0,200,255,0.15);
  border-radius:9px; padding:13px 16px; margin-top:13px;
}
.obs-url p { font-size:0.76rem; color:rgba(255,255,255,.5); margin:0 0 7px; }
.obs-url code { display:block; font-size:0.8rem; color:#00c8ff; word-break:break-all; background:rgba(0,200,255,.06); border:1px solid rgba(0,200,255,.12); border-radius:6px; padding:7px 11px; }

/* Info box */
.ls-info {
  background:rgba(145,70,255,.06); border:1px solid rgba(145,70,255,.18);
  border-radius:9px; padding:12px 16px; font-size:0.78rem; color:rgba(255,255,255,.6);
  display:flex; gap:9px; margin-top:12px;
}
.ls-info i { color:#9146FF; flex-shrink:0; margin-top:1px; }

/* Save button */
.ls-btn {
  background:linear-gradient(135deg,#9146FF,#6441A5);
  border:none; color:#fff; padding:11px 30px; border-radius:9px;
  font-weight:700; font-size:0.88rem; letter-spacing:.3px; cursor:pointer;
  transition:opacity .2s, transform .2s;
}
.ls-btn:hover { opacity:.9; transform:translateY(-1px); }

.ls-back { font-size:0.83rem; color:var(--text-muted,#8a94a6); text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
.ls-back:hover { color:#9146FF; }

.ls-configured { margin-left:auto; font-size:.7rem; font-weight:700; }
.ls-configured.tw { color:#9146FF; }
.ls-configured.yt { color:#FF0000; }
.ls-configured.dc { color:#5865F2; }
</style>
@endsection

@section('content')
<div class="ls-wrap">

  @include('flash::message')

  {{-- Hero --}}
  <div class="ls-hero">
    <div class="ls-hero-inner">
      <h2><i class="fas fa-satellite-dish me-2"></i>Stream Settings</h2>
      <p>Liga as tuas plataformas. Quando voares ao vivo, o teu status aparece no Live Map e na lista de pilotos da VA!</p>
      @if($user->is_live ?? false)
        <div class="ls-live-badge"><div class="ls-live-dot"></div>ESTÁS LIVE AGORA</div>
      @else
        <div class="ls-offline-badge">● OFFLINE</div>
      @endif
    </div>
  </div>

  <form method="POST" action="{{ route('livestream.profile.store') }}">
    @csrf

    {{-- Twitch --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-icon ic-tw"><i class="fab fa-twitch"></i></div>
        <h5>Twitch</h5>
        @if($user->twitch_username)
          <span class="ls-configured tw"><i class="fas fa-check-circle me-1"></i>Configurado</span>
        @endif
      </div>
      <div class="ls-card-body">
        <label class="ls-label" for="twitch_username">Nome de utilizador do canal</label>
        <div class="ls-ig">
          <span class="ls-pfx">twitch.tv/</span>
          <input type="text" name="twitch_username" id="twitch_username" class="ls-inp"
            value="{{ old('twitch_username', $user->twitch_username ?? '') }}"
            placeholder="o_teu_username" autocomplete="off">
        </div>
        <div class="ls-hint">Coloca apenas o nome do canal sem o URL. Ex: <strong>flyazores</strong></div>

        @if($twitchData ?? false)
          <div class="twitch-preview">
            <div class="ls-live-dot"></div>
            <div>Estás <strong>LIVE!</strong> — "{{ $twitchData['title'] ?? '' }}" &nbsp;·&nbsp; {{ number_format($twitchData['viewer_count'] ?? 0) }} viewers</div>
          </div>
        @endif
      </div>
    </div>

    {{-- YouTube --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-icon ic-yt"><i class="fab fa-youtube"></i></div>
        <h5>YouTube</h5>
        @if($user->youtube_channel_id ?? false)
          <span class="ls-configured yt"><i class="fas fa-check-circle me-1"></i>Configurado</span>
        @endif
      </div>
      <div class="ls-card-body">
        <label class="ls-label" for="youtube_channel_id">ID do Canal YouTube</label>
        <input type="text" name="youtube_channel_id" id="youtube_channel_id" class="ls-inp solo yt"
          value="{{ old('youtube_channel_id', $user->youtube_channel_id ?? '') }}"
          placeholder="UC_x5XG1OV2P6uZZ5FSM9Ttw" autocomplete="off">
        <div class="ls-hint">
          Vai a <a href="https://www.youtube.com/account_advanced" target="_blank">youtube.com/account_advanced</a> para ver o teu Channel ID.
          Deixa em branco se usares só Twitch.
        </div>
      </div>
    </div>

    {{-- Discord Webhook --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-icon ic-dc"><i class="fab fa-discord"></i></div>
        <h5>Discord — Notificação Automática</h5>
        @if($user->discord_webhook_url ?? false)
          <span class="ls-configured dc"><i class="fas fa-check-circle me-1"></i>Ativo</span>
        @endif
      </div>
      <div class="ls-card-body">
        <label class="ls-label" for="discord_webhook">URL do Webhook</label>
        <input type="url" name="discord_webhook" id="discord_webhook" class="ls-inp solo dc"
          value="{{ old('discord_webhook', $user->discord_webhook_url ?? '') }}"
          placeholder="https://discord.com/api/webhooks/..." autocomplete="off">
        <div class="ls-hint">
          Quando iniciares um voo ao vivo, será enviada uma mensagem automática ao teu Discord.
          Cria o Webhook em <strong>Definições do Canal → Integrações → Webhooks</strong>.
        </div>
      </div>
    </div>

    {{-- OBS Overlay --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-icon ic-ob"><i class="fas fa-desktop"></i></div>
        <h5>OBS / Streamlabs Overlay</h5>
      </div>
      <div class="ls-card-body">
        <div class="ls-toggle-row">
          <div class="ls-toggle-info">
            <h6>Ativar Overlay de Telemetria</h6>
            <p>Mostra altitude, velocidade, rumo e fase de voo diretamente no OBS como browser source transparente.</p>
          </div>
          <label class="ls-sw">
            <input type="checkbox" name="obs_overlay_enabled"
              {{ ($user->obs_overlay_enabled ?? false) ? 'checked' : '' }}>
            <span class="ls-sl"></span>
          </label>
        </div>

        @if($user->obs_overlay_enabled ?? false)
          <div class="obs-url">
            <p><i class="fas fa-info-circle me-1" style="color:#00c8ff;"></i>
               Adiciona como <strong>Browser Source</strong> no OBS (1920×1080, fundo transparente):</p>
            
            <div style="display:flex; align-items:center; gap:8px;">
              <code id="obs-url-code" style="flex:1;">{{ url('/live/overlay/' . ($activePirep ?? false ? $activePirep->id : '{pirep_id}')) }}</code>
              <button type="button" onclick="copyObsUrl()" title="Copiar URL"
                style="background:rgba(0,200,255,0.15); border:1px solid rgba(0,200,255,0.3); color:#00c8ff;
                       border-radius:6px; padding:6px 10px; cursor:pointer; font-size:0.8rem; flex-shrink:0;
                       transition:background .2s;">
                <i class="fas fa-copy"></i>
              </button>
            </div>

            <p style="margin-top:12px; margin-bottom:4px; font-size:0.78rem; color:rgba(255,255,255,0.55);">Substitui</p>
            
            @if($activePirep ?? false)
              <code style="background:rgba(16,185,129,0.1); border-color:rgba(16,185,129,0.3); color:#34d399;">
                <i class="fas fa-check-circle me-1"></i>{{ $activePirep->id }}
              </code>
              <p style="margin-top:6px; font-size:0.75rem; color:#34d399;">
                Voo <strong>{{ $activePirep->ident }}</strong> detetado em curso! O ID já foi substituído acima automaticamente.
              </p>
            @else
              <code>{pirep_id}</code>
              <p style="margin-top:6px; font-size:0.75rem; color:rgba(255,255,255,0.55);">
                pelo ID do teu PIREP ativo quando voares.
              </p>
            @endif
          </div>
        @endif

        <div class="ls-info">
          <i class="fas fa-lightbulb"></i>
          <div>
            <strong>Como funciona?</strong> Com o teu voo <em>In Progress</em> no vmsACARS, os dados de telemetria são
            atualizados a cada 8 segundos no overlay. No OBS, em <strong>Browser Source</strong>, ativa a opção
            <em>"Page background transparent"</em>.
          </div>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
      <a href="{{ url()->previous() }}" class="ls-back">
        <i class="fas fa-arrow-left me-1"></i>Voltar
      </a>
      <button type="submit" class="ls-btn">
        <i class="fas fa-save me-2"></i>Guardar
      </button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
@parent
<script>
function copyObsUrl() {
    const txt = document.getElementById('obs-url-code')?.textContent ?? '';
    if (!txt) return;
    navigator.clipboard.writeText(txt).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = '#34d399';
        setTimeout(() => { btn.innerHTML = orig; btn.style.color = '#00c8ff'; }, 2000);
    });
}
</script>
@endsection
