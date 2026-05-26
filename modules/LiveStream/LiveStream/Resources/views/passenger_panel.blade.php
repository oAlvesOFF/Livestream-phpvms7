@extends('app')
@section('title', 'Painel do Passageiro - Voo ' . $pirep->ident)

@section('content')
<style>
    .passenger-panel {
        font-family: 'Inter', sans-serif;
        color: #ffffff;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    .stream-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        height: 0;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .stream-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    .telemetry-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        transition: transform 0.3s ease;
    }
    .telemetry-card:hover {
        transform: translateY(-5px);
    }
    .telemetry-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #00d2ff;
    }
    .telemetry-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
    }
    .menu-card {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    .btn-snack {
        background: linear-gradient(45deg, #ff9966, #ff5e62);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-snack:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255, 94, 98, 0.4);
    }
    .btn-snack:disabled {
        background: #555;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="passenger-panel">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="mb-3">
                            <i class="fas fa-plane-departure"></i> Voo {{ $pirep->ident }} 
                            @if($isActive)
                                <span class="badge badge-success pulse">LIVE AGORA</span>
                            @else
                                <span class="badge badge-secondary">FINALIZADO</span>
                            @endif
                        </h2>
                        <h5>Piloto: {{ $user->name }}</h5>
                        
                        <div class="stream-container mt-4">
                            @if($user->is_live && $user->current_stream_url)
                                <iframe src="{{ $user->current_stream_url }}&parent={{ request()->getHost() }}" allowfullscreen></iframe>
                            @else
                                <div class="d-flex justify-content-center align-items-center" style="height: 100%; background: #000; color: #fff;">
                                    <h5>Transmissão Offline</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h4 class="mb-3 border-bottom pb-2">Telemetria</h4>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="telemetry-card">
                                    <div class="telemetry-value" id="alt-val">{{ $pirep->position ? $pirep->position->altitude : 0 }}</div>
                                    <div class="telemetry-label">Altitude (ft)</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="telemetry-card">
                                    <div class="telemetry-value" id="gs-val">{{ $pirep->position ? $pirep->position->gs : 0 }}</div>
                                    <div class="telemetry-label">Velocidade (kts)</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="telemetry-card">
                                    <div class="telemetry-value" id="hdg-val">{{ $pirep->position ? $pirep->position->heading : 0 }}</div>
                                    <div class="telemetry-label">Proa</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="telemetry-card">
                                    <div class="telemetry-value" id="dist-val">{{ $pirep->distance }}</div>
                                    <div class="telemetry-label">Milhas (nm)</div>
                                </div>
                            </div>
                        </div>

                        <div class="menu-card text-center">
                            <h4><i class="fas fa-utensils"></i> Serviço de Bordo</h4>
                            <p class="text-muted small">Ofereça um lanche para melhorar o conforto do voo e gerar pontos bônus para o Comandante!</p>
                            
                            @if($menuType == 'short')
                                <p><strong>Rota Curta:</strong> Serviço de Bebidas e Snacks.</p>
                                <button class="btn-snack" onclick="sendInteraction('cafe')">☕ Oferecer Café</button>
                            @elseif($menuType == 'medium')
                                <p><strong>Rota Média:</strong> Sanduíches e Bebidas.</p>
                                <button class="btn-snack" onclick="sendInteraction('sanduiche')">🥪 Enviar Sanduíche</button>
                            @else
                                <p><strong>Rota Longa:</strong> Serviço Completo de Jantar.</p>
                                <button class="btn-snack" onclick="sendInteraction('jantar')">🍝 Oferecer Jantar Quente</button>
                            @endif
                            <br><br>
                            <div id="interaction-msg" style="display: none;" class="alert alert-info small p-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Real-time telemetry update (polling example)
    // In a real scenario, this could use Laravel Echo
    const pirepId = '{{ $pirep->id }}';
    
    function fetchTelemetry() {
        if('{{ $isActive }}' == '1') {
            $.get('/api/pireps/' + pirepId + '/acars', function(data) {
                if(data && data.data && data.data.length > 0) {
                    let pos = data.data[0]; // Latest position
                    $('#alt-val').text(pos.altitude);
                    $('#gs-val').text(pos.gs);
                    $('#hdg-val').text(pos.heading);
                }
            });
        }
    }
    
    // Poll every 10 seconds
    setInterval(fetchTelemetry, 10000);

    function sendInteraction(type) {
        let btn = $('.btn-snack');
        btn.prop('disabled', true);
        
        $.post('{{ route('livestream.passenger.interact', $pirep->id) }}', {
            _token: '{{ csrf_token() }}',
            type: type
        })
        .done(function(res) {
            $('#interaction-msg').removeClass('alert-danger alert-warning').addClass('alert-success').text(res.message).fadeIn();
        })
        .fail(function(err) {
            let msg = err.responseJSON ? err.responseJSON.error : 'Erro desconhecido.';
            $('#interaction-msg').removeClass('alert-success alert-info').addClass('alert-warning').text(msg).fadeIn();
            setTimeout(() => { btn.prop('disabled', false); }, 5000);
        });
    }
</script>
@endsection
