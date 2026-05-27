@extends('app')
@section('title', 'Stream Settings')

@section('css')
@parent
<style>
/* ── Base ── */
.ls-wrap { max-width: 780px; margin: 0 auto; padding: 0 0 40px; }

/* ── Hero Header ── */
.ls-hero {
  background: linear-gradient(135deg, #6441a5 0%, #2a0845 100%);
  border-radius: 16px;
  padding: 32px 36px;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
}
.ls-hero::before {
  content:'';
  position:absolute; top:-60px; right:-60px;
  width:260px; height:260px;
  background:rgba(255,255,255,0.04);
  border-radius:50%;
}
.ls-hero::after {
  content:'';
  position:absolute; bottom:-80px; left:-40px;
  width:300px; height:300px;
  background:rgba(255,255,255,0.03);
  border-radius:50%;
}
.ls-hero-inner { position:relative; z-index:1; }
.ls-hero h2 {
  color:#fff; font-weight:800; font-size:1.6rem; margin:0 0 6px;
  display:flex; align-items:center; gap:10px;
}
.ls-hero p { color:rgba(255,255,255,0.65); font-size:0.9rem; margin:0; }
.ls-live-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(220,53,69,0.25);
  border:1px solid rgba(220,53,69,0.4);
  border-radius:20px; padding:4px 14px;
  font-size:0.72rem; font-weight:800; color:#ff6b7a;
  letter-spacing:1px; margin-top:12px;
}
.ls-live-dot {
  width:7px; height:7px; background:#ff4d5e;
  border-radius:50%; animation:pulseDot 1.4s infinite;
}
.ls-offline-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,255,255,0.07);
  border:1px solid rgba(255,255,255,0.12);
  border-radius:20px; padding:4px 14px;
  font-size:0.72rem; font-weight:700; color:rgba(255,255,255,0.5);
  letter-spacing:1px; margin-top:12px;
}
@keyframes pulseDot {
  0%,100%{opacity:1;transform:scale(1);}
  50%{opacity:0.4;transform:scale(0.75);}
}

/* ── Cards ── */
.ls-card {
  background: var(--bg-card, #1a1d21);
  border: 1px solid var(--border-color, rgba(255,255,255,0.07));
  border-radius: 14px;
  margin-bottom: 18px;
  overflow: hidden;
}
.ls-card-header {
  padding: 14px 22px;
  border-bottom: 1px solid var(--border-color, rgba(255,255,255,0.06));
  display: flex; align-items: center; gap: 10px;
}
.ls-card-header h5 {
  margin:0; font-size:0.92rem; font-weight:700;
  color: var(--text-heading, #fff);
}
.ls-card-icon {
  width:32px; height:32px; border-radius:8px;
  display:flex; align-items:center; justify-content:center; font-size:15px;
  flex-shrink:0;
}
.icon-twitch  { background:rgba(145,70,255,0.15); color:#9146FF; }
.icon-youtube { background:rgba(255,0,0,0.12);    color:#FF0000; }
.icon-discord { background:rgba(88,101,242,0.15);  color:#5865F2; }
.icon-obs     { background:rgba(0,200,255,0.1);    color:#00c8ff; }
.ls-card-body { padding: 20px 22px; }

/* ── Inputs ── */
.ls-label {
  display:block; font-size:0.72rem; font-weight:700;
  text-transform:uppercase; letter-spacing:0.8px;
  color: var(--text-muted, #8a94a6); margin-bottom:7px;
}
.ls-input-group { display:flex; }
.ls-prefix {
  background:rgba(255,255,255,0.05);
  border:1px solid rgba(255,255,255,0.1); border-right:none;
  border-radius:8px 0 0 8px; padding:10px 13px;
  font-size:0.82rem; color:var(--text-muted,#8a94a6);
  font-weight:500; flex-shrink:0;
}
.ls-input {
  flex:1;
  background:rgba(255,255,255,0.05);
  border:1px solid rgba(255,255,255,0.1);
  color:var(--text-body,#e9ecef);
  border-radius:0 8px 8px 0;
  padding:10px 14px; font-size:0.88rem;
  transition:border-color .2s, box-shadow .2s;
  width:100%;
}
.ls-input.no-prefix { border-radius:8px; }
.ls-input:focus {
  border-color:#9146FF;
  box-shadow:0 0 0 3px rgba(145,70,255,0.15);
  background:rgba(255,255,255,0.07); color:#fff; outline:none;
}
.ls-input.yt:focus { border-color:#FF0000; box-shadow:0 0 0 3px rgba(255,0,0,0.12); }
.ls-input.dc:focus { border-color:#5865F2; box-shadow:0 0 0 3px rgba(88,101,242,0.15); }
.ls-hint { font-size:0.75rem; color:var(--text-muted,#8a94a6); margin-top:6px; }
.ls-hint a { color:#9146FF; text-decoration:none; }

/* ── OBS Toggle ── */
.ls-toggle-row {
  display:flex; align-items:center; justify-content:space-between;
}
.ls-toggle-info h6 { margin:0 0 3px; font-size:0.9rem; font-weight:700; color:var(--text-heading,#fff); }
.ls-toggle-info p  { margin:0; font-size:0.78rem; color:var(--text-muted,#8a94a6); }
.ls-switch { position:relative; width:46px; height:26px; flex-shrink:0; }
.ls-switch input { opacity:0; width:0; height:0; }
.ls-slider {
  position:absolute; cursor:pointer; top:0;left:0;right:0;bottom:0;
  background:rgba(255,255,255,0.1); border-radius:13px;
  transition:.3s;
}
.ls-slider:before {
  content:''; position:absolute; width:20px; height:20px;
  left:3px; top:3px; background:#fff; border-radius:50%; transition:.3s;
}
input:checked + .ls-slider { background:#9146FF; }
input:checked + .ls-slider:before { transform:translateX(20px); }

/* ── OBS URL box ── */
.obs-url-box {
  background:rgba(0,200,255,0.06);
  border:1px solid rgba(0,200,255,0.18);
  border-radius:10px; padding:14px 18px; margin-top:14px;
}
.obs-url-box p { font-size:0.78rem; color:rgba(255,255,255,0.55); margin:0 0 8px; }
.obs-url-box code {
  display:block; font-size:0.82rem; color:#00c8ff;
  word-break:break-all; background:rgba(0,200,255,0.07);
  border:1px solid rgba(0,200,255,0.15); border-radius:6px;
  padding:8px 12px;
}

/* ── Status preview box (Twitch live) ── */
.twitch-live-preview {
  background:rgba(145,70,255,0.08);
  border:1px solid rgba(145,70,255,0.25);
  border-radius:10px; padding:12px 16px; margin-top:14px;
  display:flex; align-items:center; gap:12px;
}
.twitch-live-preview .ls-live-dot { flex-shrink:0; }
.twitch-live-preview div { font-size:0.82rem; color:rgba(255,255,255,0.75); }
.twitch-live-preview strong { color:#9146FF; }

/* ── Save button ── */
.ls-save-btn {
  background:linear-gradient(135deg,#9146FF,#6441A5);
  border:none; color:#fff; padding:12px 32px;
  border-radius:10px; font-weight:700; font-size:0.9rem;
  letter-spacing:.3px; cursor:pointer;
  transition:opacity .2s, transform .2s;
}
.ls-save-btn:hover { opacity:.9; transform:translateY(-1px); }

/* ── Info alert ── */
.ls-info-box {
  background:rgba(145,70,255,0.07);
  border:1px solid rgba(145,70,255,0.2);
  border-radius:10px; padding:14px 18px;
  font-size:0.8rem; color:rgba(255,255,255,0.6);
  display:flex; gap:10px;
}
.ls-info-box i { color:#9146FF; flex-shrink:0; margin-top:1px; }

/* ── Back link ── */
.ls-back { font-size:0.85rem; color:var(--text-muted,#8a94a6); text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
.ls-back:hover { color:#9146FF; }
</style>
@endsection

@section('content')
<div class="ls-wrap">

  {{-- Flash messages --}}
  @include('flash::message')

  {{-- Validation errors --}}
  @if($errors->any())
    <div style="background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.3); border-radius:10px; padding:15px; margin-bottom:20px; color:#ff6b7a;">
      <ul style="margin:0; padding-left:20px;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Hero --}}
  <div class="ls-hero">
    <div class="ls-hero-inner">
      <h2>
        <i class="fas fa-satellite-dish"></i>
        Streamer Settings
      </h2>
      <p>Liga as tuas plataformas de stream. Quando fizeres um voo ao vivo, o teu status aparece automaticamente no Live Map e na lista de pilotos!</p>

      @if(optional($streamProfile)->is_live)
        <div class="ls-live-badge">
          <div class="ls-live-dot"></div>
          ESTÁS LIVE AGORA
        </div>
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
        <div class="ls-card-icon icon-twitch"><i class="fab fa-twitch"></i></div>
        <h5>Twitch</h5>
        @if(optional($streamProfile)->twitch_username)
          <span style="margin-left:auto; font-size:.72rem; color:#9146FF; font-weight:700;">
            <i class="fas fa-check-circle me-1"></i>Configurado
          </span>
        @endif
      </div>
      <div class="ls-card-body">
        <label class="ls-label" for="twitch_username">Nome de utilizador do canal</label>
        <div class="ls-input-group">
          <span class="ls-prefix">twitch.tv/</span>
          <input type="text" name="twitch_username" id="twitch_username" class="ls-input"
            value="{{ old('twitch_username', optional($streamProfile)->twitch_username ?? '') }}"
            placeholder="o_teu_username" autocomplete="off">
        </div>
        <div class="ls-hint">Escreve apenas o nome do canal (ex: <strong>flyazores</strong>), sem o URL completo.</div>

        @if($twitchData)
        <div class="twitch-live-preview">
          <div class="ls-live-dot"></div>
          <div>Estás <strong>LIVE</strong> agora! — <em>{{ $twitchData['title'] ?? '' }}</em> ({{ $twitchData['viewer_count'] ?? 0 }} viewers)</div>
        </div>
        @endif
      </div>
    </div>

    {{-- YouTube --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-card-icon icon-youtube"><i class="fab fa-youtube"></i></div>
        <h5>YouTube</h5>
        @if(optional($streamProfile)->youtube_channel_id)
          <span style="margin-left:auto; font-size:.72rem; color:#FF0000; font-weight:700;">
            <i class="fas fa-check-circle me-1"></i>Configurado
          </span>
        @endif
      </div>
      <div class="ls-card-body">
        <label class="ls-label" for="youtube_channel_id">ID do Canal YouTube</label>
        <input type="text" name="youtube_channel_id" id="youtube_channel_id" class="ls-input no-prefix yt"
          value="{{ old('youtube_channel_id', optional($streamProfile)->youtube_channel_id ?? '') }}"
          placeholder="UC_x5XG1OV2P6uZZ5FSM9Ttw" autocomplete="off">
        <div class="ls-hint">
          Encontra o teu Channel ID em <a href="https://www.youtube.com/account_advanced" target="_blank">youtube.com/account_advanced</a>. Deixa em branco se usares só Twitch.
        </div>
      </div>
    </div>

    {{-- Discord Webhook --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-card-icon icon-discord"><i class="fab fa-discord"></i></div>
        <h5>Discord Webhook (Notificação Automática)</h5>
        @if(optional($streamProfile)->discord_webhook_url ?? false)
          <span style="margin-left:auto; font-size:.72rem; color:#5865F2; font-weight:700;">
            <i class="fas fa-check-circle me-1"></i>Ativo
          </span>
        @endif
      </div>
      <div class="ls-card-body">
        <label class="ls-label" for="discord_webhook">URL do Webhook do Discord</label>
        <input type="url" name="discord_webhook" id="discord_webhook" class="ls-input no-prefix dc"
          value="{{ old('discord_webhook', optional($streamProfile)->discord_webhook_url ?? '') }}"
          placeholder="https://discord.com/api/webhooks/..." autocomplete="off">
        <div class="ls-hint">
          Quando iniciares um voo ao vivo, será enviada uma mensagem automática para o teu servidor Discord. 
          Cria um Webhook em <strong>Definições do Canal → Integrações → Webhooks</strong>.
        </div>
      </div>
    </div>

    {{-- OBS Overlay --}}
    <div class="ls-card">
      <div class="ls-card-header">
        <div class="ls-card-icon icon-obs"><i class="fas fa-desktop"></i></div>
        <h5>OBS / Streamlabs Overlay</h5>
      </div>
      <div class="ls-card-body">
        <div class="ls-toggle-row">
          <div class="ls-toggle-info">
            <h6>Ativar Overlay de Telemetria</h6>
            <p>Mostra altitude, velocidade, rumo e fase de voo diretamente no teu OBS como uma browser source transparente.</p>
          </div>
          <label class="ls-switch">
            <input type="checkbox" name="obs_overlay_enabled" id="obs_overlay_enabled" value="1"
              {{ (optional($streamProfile)->obs_overlay_enabled ?? false) ? 'checked' : '' }}>
            <span class="ls-slider"></span>
          </label>
        </div>

        @if(optional($streamProfile)->obs_overlay_enabled ?? false)
        <div class="obs-url-box">
          <p>Adiciona como <strong>Browser Source</strong> no OBS (1920×1080, fundo transparente):</p>
          
          <div style="display:flex; align-items:center; gap:8px;">
            <code id="obs-url-code" style="flex:1;">{{ url('/live/overlay/' . ($activePirep ? $activePirep->id : '{pirep_id}')) }}</code>
            <button type="button" onclick="copyObsUrl()" title="Copiar URL"
              style="background:rgba(0,200,255,0.15); border:1px solid rgba(0,200,255,0.3); color:#00c8ff;
                     border-radius:6px; padding:6px 10px; cursor:pointer; font-size:0.8rem; flex-shrink:0;
                     transition:background .2s;">
              <i class="fas fa-copy"></i>
            </button>
          </div>

          <p style="margin-top:12px; margin-bottom:4px; font-size:0.78rem; color:rgba(255,255,255,0.55);">Substitui</p>
          
          @if($activePirep)
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

        <div class="ls-info-box mt-3">
          <i class="fas fa-lightbulb"></i>
          <div>
            <strong>Como funciona?</strong> Quando o teu voo estiver <em>In Progress</em> no vmsACARS, os dados são atualizados automaticamente de 8 em 8 segundos no overlay.
            Adiciona como <strong>Browser Source</strong> no OBS, define o tamanho 1920×1080 e ativa a opção <em>"Page background color transparent"</em>.
          </div>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
      <a href="{{ url()->previous() }}" class="ls-back">
        <i class="fas fa-arrow-left"></i> Voltar
      </a>
      <button type="submit" class="ls-save-btn">
        <i class="fas fa-save me-2"></i>Guardar Definições
      </button>
    </div>
  </form>

  {{-- Passenger Panel quick link (outside form) --}}
  @if($activePirep)
  <div class="ls-card mt-4">
    <div class="ls-card-header">
      <div class="ls-card-icon" style="background:rgba(56,189,248,0.12); color:#38bdf8;">
        <i class="fas fa-users"></i>
      </div>
      <h5>Painel do Passageiro</h5>
      <span style="margin-left:auto; font-size:.72rem; color:#34d399; font-weight:700;">
        <i class="fas fa-check-circle me-1"></i>Voo Ativo
      </span>
    </div>
    <div class="ls-card-body">
      <p style="font-size:0.85rem; color:var(--text-muted,#8a94a6); margin-bottom:10px;">
        Partilha este link com os teus espectadores para que possam interagir com o teu voo em direto!
      </p>
      <div style="display:flex; align-items:center; gap:8px;">
        <code id="passenger-url-code" style="flex:1; color:#38bdf8; background:rgba(56,189,248,0.07); border:1px solid rgba(56,189,248,0.15); border-radius:6px; padding:8px 12px; font-size:0.82rem; word-break:break-all;">{{ url('/live/passenger/' . $activePirep->id) }}</code>
        <button type="button" onclick="copyPassengerUrl()" title="Copiar link"
          style="background:rgba(56,189,248,0.15); border:1px solid rgba(56,189,248,0.3); color:#38bdf8;
                 border-radius:6px; padding:6px 10px; cursor:pointer; font-size:0.8rem; flex-shrink:0;">
          <i class="fas fa-copy"></i>
        </button>
        <a href="{{ url('/live/passenger/' . $activePirep->id) }}" target="_blank"
          style="background:rgba(56,189,248,0.15); border:1px solid rgba(56,189,248,0.3); color:#38bdf8;
                 border-radius:6px; padding:6px 10px; text-decoration:none; font-size:0.8rem; flex-shrink:0;">
          <i class="fas fa-external-link-alt"></i>
        </a>
      </div>
    </div>
  </div>
  @endif

</div>
@endsection

@section('scripts')
@parent
<script>
function copyObsUrl() {
    const txt = document.getElementById('obs-url-code')?.textContent ?? '';
    navigator.clipboard.writeText(txt).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = '#34d399';
        setTimeout(() => { btn.innerHTML = orig; btn.style.color = '#00c8ff'; }, 2000);
    });
}

function copyPassengerUrl() {
    const txt = document.getElementById('passenger-url-code')?.textContent ?? '';
    navigator.clipboard.writeText(txt).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = '#34d399';
        setTimeout(() => { btn.innerHTML = orig; btn.style.color = '#38bdf8'; }, 2000);
    });
}
</script>
@endsection
