<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OBS Overlay — {{ $pirep->ident ?? 'FLY' }}</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: transparent !important;
    font-family: 'Segoe UI', 'Inter', sans-serif;
    overflow: hidden;
    width: 1920px; height: 1080px;
  }
  .overlay-wrap {
    position: absolute;
    bottom: 28px; left: 28px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  /* ── Top Strip: Airline + Flight Ident ── */
  .top-strip {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(5,15,30,0.78);
    border: 1px solid rgba(0,200,255,0.18);
    backdrop-filter: blur(12px);
    border-radius: 12px;
    padding: 10px 20px;
    width: fit-content;
  }
  .airline-code {
    font-size: 1.5rem;
    font-weight: 800;
    color: #00c8ff;
    letter-spacing: 2px;
    text-shadow: 0 0 14px rgba(0,200,255,0.5);
  }
  .divider-line {
    width: 1px; height: 28px;
    background: rgba(255,255,255,0.2);
  }
  .flight-num {
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 1px;
  }
  .route-txt {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.55);
    letter-spacing: 0.5px;
  }
  .live-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(220,53,69,0.22);
    border: 1px solid rgba(220,53,69,0.4);
    border-radius: 20px;
    padding: 3px 12px;
    margin-left: 8px;
  }
  .live-dot {
    width: 7px; height: 7px;
    background: #ff4d5e;
    border-radius: 50%;
    animation: pulse 1.4s infinite;
  }
  .live-badge span {
    font-size: 0.7rem;
    font-weight: 800;
    color: #ff6b7a;
    letter-spacing: 1px;
  }
  @keyframes pulse {
    0%,100%{opacity:1;transform:scale(1);}
    50%{opacity:0.4;transform:scale(0.75);}
  }

  /* ── Telemetry Cards ── */
  .telem-row {
    display: flex;
    gap: 8px;
  }
  .telem-card {
    background: rgba(5,15,30,0.75);
    border: 1px solid rgba(0,200,255,0.14);
    backdrop-filter: blur(12px);
    border-radius: 10px;
    padding: 10px 18px;
    min-width: 108px;
    text-align: center;
  }
  .telem-val {
    font-size: 1.45rem;
    font-weight: 800;
    color: #00c8ff;
    line-height: 1;
    text-shadow: 0 0 10px rgba(0,200,255,0.4);
  }
  .telem-unit {
    font-size: 0.62rem;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 3px;
  }
  .telem-label {
    font-size: 0.68rem;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-top: 1px;
  }

  /* ── Phase pill ── */
  .phase-pill {
    background: rgba(5,15,30,0.75);
    border: 1px solid rgba(255,200,0,0.2);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 6px 16px;
    font-size: 0.8rem;
    font-weight: 700;
    color: #ffc107;
    letter-spacing: 0.8px;
    display: flex;
    align-items: center;
    gap: 7px;
    width: fit-content;
  }

  /* ── FlyAzores Branding ── */
  .branding {
    position: absolute;
    bottom: 28px; right: 28px;
    background: rgba(5,15,30,0.72);
    border: 1px solid rgba(0,200,255,0.12);
    backdrop-filter: blur(12px);
    border-radius: 10px;
    padding: 8px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .branding-name {
    font-size: 0.9rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: 1.5px;
  }
  .branding-site {
    font-size: 0.65rem;
    color: rgba(255,255,255,0.45);
    letter-spacing: 0.5px;
  }
  .branding-dot {
    width: 8px; height: 8px;
    background: linear-gradient(135deg,#00c8ff,#0066ff);
    border-radius: 50%;
    flex-shrink: 0;
  }
</style>
</head>
<body>

<div class="overlay-wrap">

  {{-- Top flight strip --}}
  <div class="top-strip">
    <div class="airline-code">{{ optional($pirep->airline)->icao ?? 'FAZ' }}</div>
    <div class="divider-line"></div>
    <div>
      <div class="flight-num">{{ $pirep->ident }}</div>
      <div class="route-txt">
        {{ optional($pirep->dep_airport)->icao ?? '---' }}
        &nbsp;→&nbsp;
        {{ optional($pirep->arr_airport)->icao ?? '---' }}
      </div>
    </div>
    @if($isActive)
    <div class="live-badge">
      <div class="live-dot"></div>
      <span>LIVE</span>
    </div>
    @endif
  </div>

  {{-- Telemetry row --}}
  <div class="telem-row">
    <div class="telem-card">
      <div class="telem-val" id="alt-val">
        {{ optional($pirep->position)->altitude ?? '---' }}
      </div>
      <div class="telem-unit">ft</div>
      <div class="telem-label">Altitude</div>
    </div>
    <div class="telem-card">
      <div class="telem-val" id="gs-val">
        {{ optional($pirep->position)->gs ?? '---' }}
      </div>
      <div class="telem-unit">kts</div>
      <div class="telem-label">Velocidade</div>
    </div>
    <div class="telem-card">
      <div class="telem-val" id="hdg-val">
        {{ optional($pirep->position)->heading ?? '---' }}°
      </div>
      <div class="telem-unit">&nbsp;</div>
      <div class="telem-label">Rumo</div>
    </div>
    <div class="telem-card">
      <div class="telem-val" id="vs-val">
        {{ optional($pirep->position)->vs ?? '---' }}
      </div>
      <div class="telem-unit">fpm</div>
      <div class="telem-label">Vert. Speed</div>
    </div>
    <div class="telem-card">
      <div class="telem-val" id="dist-val">
        {{ is_object($pirep->distance) ? round($pirep->distance->internal()) : ($pirep->distance ?? '---') }}
      </div>
      <div class="telem-unit">nm</div>
      <div class="telem-label">Distância</div>
    </div>
  </div>

  {{-- Flight phase --}}
  <div class="phase-pill" id="phase-pill">
    ✈️ <span id="phase-txt">Em Rota</span>
  </div>

</div>

{{-- Branding --}}
<div class="branding">
  <div class="branding-dot"></div>
  <div>
    <div class="branding-name">FLYAZORES VIRTUAL</div>
    <div class="branding-site">flyazoresvirtual.com</div>
  </div>
</div>

<script>
const pirepId = '{{ $pirep->id }}';
const isActive = {{ $isActive ? 'true' : 'false' }};

function fetchTelemetry() {
  if (!isActive) return;
  fetch('/api/pireps/' + pirepId + '/acars')
    .then(r => r.ok ? r.json() : null)
    .then(data => {
      if (!data || !data.data || !data.data.length) return;
      const pos = data.data[0];
      if (pos.altitude !== undefined) document.getElementById('alt-val').textContent = Math.round(pos.altitude).toLocaleString();
      if (pos.gs       !== undefined) document.getElementById('gs-val').textContent  = Math.round(pos.gs);
      if (pos.heading  !== undefined) document.getElementById('hdg-val').textContent = Math.round(pos.heading) + '°';
      if (pos.vs       !== undefined) document.getElementById('vs-val').textContent  = Math.round(pos.vs);

      // Phase detection
      const vs = pos.vs ?? 0;
      const gs = pos.gs ?? 0;
      const alt = pos.altitude ?? 0;
      let phase = '✈️ Em Rota';
      if (gs < 5)         phase = '🅿️ Em Terra';
      else if (vs > 300)  phase = '⬆️ A Subir';
      else if (vs < -300) phase = '⬇️ A Descer';
      else if (gs > 10 && alt < 2000) phase = '🛬 Aproximação';
      document.getElementById('phase-txt').textContent = phase;
    })
    .catch(() => {});
}

fetchTelemetry();
setInterval(fetchTelemetry, 8000);
</script>
</body>
</html>
