# LiveStream Module for phpVMS 7

[English](#english) | [Português](#português)

---

<a id="english"></a>
## English

phpVMS v7 module for Live Streaming Integration, OBS Overlay and Passenger Panel

### Features
- Pilot Stream Settings page (link Twitch, YouTube, Discord Webhook, OBS Overlay)
- Live status detection on Twitch and YouTube via API
- Auto Discord webhook notification when pilot goes live
- Transparent OBS Overlay (`/live/overlay/{pirep_id}`) with real-time telemetry
- Public Passenger Panel (`/live/passenger/{pirep_id}`) for viewers
- Smart active PIREP detection — auto-fills the OBS URL with the real PIREP ID
- Passenger interaction system
- Live badge visible on the Live Map and pilot roster
- No core file modifications — fully self-contained module

### Compatibility
This module is fully compatible with phpVMS v7 and utilizes the standard module system.

### Installation
1. Upload the `LiveStream` folder to your phpVMS root `/modules` directory.
2. Go to admin → addons/modules, find **LiveStream** and click **Enable**.
3. Run migrations via admin → dashboard (or visit `/update`).
4. Clear `application` cache.

### Configuration
Configure Twitch and YouTube API credentials in your phpVMS `.env` file:
```env
TWITCH_CLIENT_ID=your_client_id
TWITCH_CLIENT_SECRET=your_client_secret
YOUTUBE_API_KEY=your_youtube_api_key
```

### License
MIT License

---

<a id="português"></a>
## Português

Módulo phpVMS v7 para integração de Live Streaming, Overlay para OBS e Painel de Passageiros.

### Funcionalidades
- Página de configurações de stream para pilotos (Twitch, YouTube, Discord, OBS Overlay)
- Deteção automática de estado live no Twitch e YouTube via API
- Notificação automática no Discord quando o piloto inicia a stream
- Overlay transparente para OBS (`/live/overlay/{pirep_id}`) com telemetria em tempo real
- Painel público de passageiros (`/live/passenger/{pirep_id}`) para espectadores
- Deteção inteligente de PIREP ativo
- Sistema de interações de passageiros
- Badge LIVE visível no Live Map e na lista de pilotos
- Sem modificações ao core — módulo totalmente independente

### Compatibilidade
Módulo totalmente compatível com phpVMS v7, utilizando o sistema de módulos padrão.

### Instalação
1. Faça o upload da pasta `LiveStream` para o diretório `/modules` na raiz do seu phpVMS.
2. Vá ao painel administrativo → addons/modules, encontre o **LiveStream** e clique em **Enable**.
3. Execute as migrações no painel administrativo → dashboard (ou visite `/update`).
4. Limpe a cache `application`.

### Configuração
Configure as credenciais da API do Twitch e YouTube no ficheiro `.env` do seu phpVMS:
```env
TWITCH_CLIENT_ID=seu_client_id
TWITCH_CLIENT_SECRET=seu_client_secret
YOUTUBE_API_KEY=sua_youtube_api_key
```

### Licença
Licença MIT
