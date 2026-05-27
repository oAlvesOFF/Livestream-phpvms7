# LiveStream Module for phpVMS 7

phpVMS v7 module for Live Streaming Integration, OBS Overlay and Passenger Panel

> [!IMPORTANT]
> * Minimum required phpVMS v7 version is `phpVMS 7.0.0-dev` (tested on 7.0.52+)
> * This module does **NOT** modify any core phpVMS files

> [!TIP]
> * Module supports **php 8.1+** and **Laravel 10**
> * Module blades are designed for themes using **Bootstrap v5.x** and **FontAwesome v5.x** / **v6.x**
> * Compatible with **vmsACARS** for real-time telemetry

This module integrates streaming platforms (Twitch, YouTube), Discord notifications, real-time ACARS telemetry, an interactive passenger panel and a transparent OBS/Streamlabs overlay into phpVMS 7. Provides:

* Pilot Stream Settings page (link Twitch, YouTube, Discord Webhook, OBS Overlay)
* Live status detection on Twitch and YouTube via API
* Auto Discord webhook notification when pilot goes live
* Transparent OBS Overlay (`/live/overlay/{pirep_id}`) with real-time telemetry
* Public Passenger Panel (`/live/passenger/{pirep_id}`) for viewers
* Smart active PIREP detection — auto-fills the OBS URL with the real PIREP ID
* Passenger interaction system (reactions during live flights, bonus points awarded on PIREP acceptance)
* Live badge visible on the Live Map and pilot roster when a pilot is streaming
* Event listeners for automatic live status sync with phpVMS pirep state changes
* No core file modifications — fully self-contained module

---

## Compatibility

This module is fully compatible with phpVMS v7. It uses the standard module system (nWidart/laravel-modules) and hooks into phpVMS events via Event Listeners without patching any core files.

The module is designed to work best alongside **vmsACARS**, which provides the ACARS position data used by the overlay and passenger panel. Any ACARS software that is 100% compatible with phpVMS v7 and submits position data to `/api/pireps/{id}/acars/position` will also work.

---

## Installation and Updates

**Manual Install:**
1. Upload the `LiveStream` folder to your phpVMS root `/modules` directory via FTP or your control panel's file manager
2. Go to admin → addons/modules, find **LiveStream** and click **Enable**
3. Go to admin → dashboard (or visit `/update`) to run the module migrations
4. After migrations complete, go to admin → maintenance and clear `application` cache

**GitHub Clone:**
```bash
cd /path/to/phpvms/modules
git clone https://github.com/your-repo/LiveStream.git LiveStream
```
Then enable and migrate as above.

> [!WARNING]
> There is a known bug in phpVMS v7 core which can cause an error page when enabling/disabling modules manually. If you see a server error when enabling the module, simply close that tab and re-open the admin area in a new tab. The module will be enabled. Clean your `application` cache afterwards to confirm.

### Updating

Simply overwrite the module files with the new version, visit `/update` to run any new migrations, then clean `application` cache.

---

## Database Migrations

The module runs **3 migrations** that add columns to the existing `users` table and create one new table. No core tables are deleted or altered destructively.

### Columns added to `users` table

| Column | Type | Description |
|---|---|---|
| `twitch_username` | `string` (nullable) | Pilot's Twitch channel username |
| `youtube_channel_id` | `string` (nullable) | Pilot's YouTube Channel ID |
| `is_live` | `boolean` (default: false) | Whether the pilot is currently streaming live |
| `current_stream_url` | `string` (nullable) | Active stream URL when pilot is live |
| `discord_webhook_url` | `string 500` (nullable) | Discord webhook URL for live notifications |
| `obs_overlay_enabled` | `boolean` (default: false) | Whether the pilot has the OBS overlay activated |

### New table: `passenger_interactions`

Stores viewer interactions (reactions, messages) submitted via the Passenger Panel during a live flight.

| Column | Type | Description |
|---|---|---|
| `id` | `bigint` | Primary key |
| `pirep_id` | `string` | PIREP the interaction belongs to |
| `viewer_name` | `string` | Name entered by the viewer |
| `interaction_type` | `string` | Type of interaction (e.g. `coffee`, `applause`) |
| `points_awarded` | `integer` | Bonus points given to the pilot for this interaction |
| `created_at` / `updated_at` | `timestamp` | Standard Laravel timestamps |

---

## Module Routes and Links

The module registers all its own routes independently — no changes to `app/Providers/RouteServiceProvider.php` are required.

```php
// Named Routes and URLs

livestream.profile.index    GET  /live/profile          // Pilot stream settings (auth required)
livestream.profile.store    POST /live/profile          // Save stream settings (auth required)

livestream.passenger.show   GET  /live/passenger/{id}   // Public passenger panel (no auth)
livestream.passenger.interact POST /live/passenger/{id}/interact // Viewer interaction endpoint

livestream.overlay.show     GET  /live/overlay/{id}     // Transparent OBS overlay (no auth)
```

### Adding links to your theme

To add a link to the Stream Settings page in your theme navbar or profile menu:

```html
<!-- Navbar / menu link -->
<a href="{{ route('livestream.profile.index') }}" class="nav-link">
  <i class="fas fa-satellite-dish me-1"></i> Stream Settings
</a>

<!-- Share the Passenger Panel with viewers (when pirep is active) -->
<a href="{{ url('/live/passenger/' . $pirep->id) }}" target="_blank">
  <i class="fas fa-users me-1"></i> Passenger Panel
</a>

<!-- Direct URL (useful for copy-paste instructions) -->
https://your-va-site.com/live/overlay/{pirep_id}
https://your-va-site.com/live/passenger/{pirep_id}
```

---

## Pilot Stream Settings (`/live/profile`)

This is the main configuration page for pilots. It contains:

* **Twitch** — Enter a Twitch channel username (e.g. `flyazores`). If the Twitch API is configured, the page will show a live preview with viewer count and stream title when the pilot is live.
* **YouTube** — Enter a YouTube Channel ID (found at [youtube.com/account_advanced](https://www.youtube.com/account_advanced)). Leave blank if using Twitch only.
* **Discord Webhook** — Paste a Discord Webhook URL. A notification is sent automatically when the pilot starts a live flight.
* **OBS Overlay Toggle** — Enable/disable the transparent OBS overlay. When enabled, the module shows the full Browser Source URL.

### Smart PIREP Detection

When a pilot visits `/live/profile` and has a flight **In Progress** via vmsACARS, the module automatically:

1. Detects the active PIREP
2. Pre-fills the OBS URL with the **real PIREP ID** (replacing the `{pirep_id}` placeholder)
3. Shows a green confirmation badge with the flight ident (e.g. `FAZ1234`)
4. Provides a one-click copy button for both the OBS URL and the Passenger Panel link

If no flight is active, the placeholder `{pirep_id}` is shown with instructions to replace it when flying.

---

## OBS / Streamlabs Overlay (`/live/overlay/{pirep_id}`)

A fully transparent HTML page designed to be used as a **Browser Source** in OBS Studio or Streamlabs.

**Setup in OBS:**
1. Add a new Source → **Browser**
2. Set the URL to `https://your-va.com/live/overlay/{pirep_id}` (replace with real ID)
3. Set Width: `1920`, Height: `1080`
4. Check **"Page background color transparent"**
5. Click OK

**What it displays:**
* Top strip with Airline ICAO, Flight Number, Route (origin → destination)
* Live badge (green pulsing dot) when the PIREP is In Progress
* Telemetry cards: Altitude (ft), Ground Speed (kts), Heading (°), Vertical Speed (fpm), Distance (nm)
* Flight Phase pill: 🅿️ On Ground / ⬆️ Climbing / ➡️ En Route / ⬇️ Descending / 🛬 Approach
* VA Branding block (bottom right)

**Telemetry Updates:**  
Data is fetched from the phpVMS API (`/api/pireps/{id}/acars`) every **8 seconds**. Numbers animate with a subtle glow effect when they change. If the PIREP is not In Progress, the overlay displays static data from the last known position.

> [!TIP]
> The overlay works best in widescreen (16:9) layouts. If your stream is 1280x720, OBS will automatically scale it down. No changes are needed.

---

## Passenger Panel (`/live/passenger/{pirep_id}`)

A public-facing webpage that viewers can open in their browser while watching the stream. Share the URL in your stream chat or Discord.

**What it displays:**
* Flight header (airline, flight number, route, departure time)
* Live telemetry (identical to the OBS overlay): Altitude, Speed, Heading, VS, Distance
* Flight phase indicator
* Viewer interaction buttons (e.g. ☕ Offer Coffee, 👏 Applause) — sends a reaction that is logged in the database
* Interaction counter (how many reactions the pilot has received)

**Polling:**  
Telemetry auto-refreshes every **10 seconds** via JavaScript `fetch()`. No page reloads are needed.

**Viewer Interactions:**  
When a viewer clicks an interaction button, the action is stored in the `passenger_interactions` table with the PIREP ID and a `points_awarded` value. When the PIREP is accepted (flight completed), the module automatically totals all interaction points and credits them to the pilot's journal via phpVMS `JournalService`.

---

## Event Listeners

The module registers two event listeners that hook into the standard phpVMS event system:

| Listener | Event | Action |
|---|---|---|
| `CheckLiveOnStateChange` | `PirepStateChange` | When PIREP goes `IN_PROGRESS`: dispatches a background job to check Twitch/YouTube live status. When PIREP is completed/cancelled: sets `is_live = false` and clears `current_stream_url`. |
| `ProcessInteractionsOnPirepAccepted` | `PirepAccepted` | When PIREP is accepted: totals all `passenger_interactions.points_awarded` for that PIREP and credits the pilot via `JournalService`. |

### Background Job: `CheckLiveStatusJob`

Dispatched asynchronously when a pilot starts a PIREP (`IN_PROGRESS`). The job:

1. Calls the Twitch Helix API (if `TWITCH_CLIENT_ID` and `TWITCH_CLIENT_SECRET` are set in `.env`)
2. Calls the YouTube Data API v3 (if `YOUTUBE_API_KEY` is set in `.env`)
3. If either platform returns an active stream, sets `is_live = true` and stores `current_stream_url`
4. If neither is live, leaves `is_live = false`

The live status is then visible on the phpVMS Live Map and pilot roster (if your theme reads `$user->is_live`).

---

## Twitch API Setup (Optional)

To enable live status detection and the in-page Twitch preview, you need a Twitch Developer Application.

1. Go to [dev.twitch.tv/console/apps](https://dev.twitch.tv/console/apps)
2. Click **Register Your Application**
3. Set OAuth Redirect URL to `https://your-va.com`
4. Copy the **Client ID** and generate a **Client Secret**
5. Add to your phpVMS `.env` file:

```env
TWITCH_CLIENT_ID=your_client_id_here
TWITCH_CLIENT_SECRET=your_client_secret_here
```

> [!NOTE]
> Without Twitch API credentials, the module still works fully. Pilots can still enter their username, the OBS overlay and Passenger Panel function normally, and Discord webhooks still fire. Only the live status auto-detection and the in-page Twitch preview will be inactive.

---

## YouTube API Setup (Optional)

1. Go to [console.cloud.google.com](https://console.cloud.google.com)
2. Create a new project → Enable the **YouTube Data API v3**
3. Create an API Key (restrict to your server IP for security)
4. Add to `.env`:

```env
YOUTUBE_API_KEY=your_youtube_api_key_here
```

---

## Discord Webhook Setup

No API credentials needed. Pilots configure this themselves per-account:

1. In your Discord server, go to the channel you want notifications in
2. Click **Edit Channel → Integrations → Webhooks → New Webhook**
3. Copy the Webhook URL
4. Paste it into the **Stream Settings** page at `/live/profile`

The webhook fires automatically when the module detects the pilot is live (triggered by the `CheckLiveStatusJob`).

---

## Theme Integration

The module's views (`/live/profile`, `/live/overlay/`, `/live/passenger/`) use `@extends('app')` and are theme-independent. They will inherit your active phpVMS theme's base layout.

For themes that have their own Stream Settings view (like `ASA_THEME`), a patched version is included in the `theme_files_backup` folder of this release. Copy the relevant file to your theme folder:

```
theme_files_backup/
  ASA_THEME/
    navbar.blade.php                    → resources/views/layouts/ASA_THEME/navbar.blade.php
    profile/stream_settings.blade.php   → resources/views/layouts/ASA_THEME/profile/stream_settings.blade.php
```

> [!IMPORTANT]
> The `stream_settings.blade.php` in your theme must pass the `$activePirep` variable from the controller. The module's `ProfileController` already provides this. If you use a custom theme controller, make sure it calls `Pirep::where('user_id', $user->id)->where('state', PirepState::IN_PROGRESS)->first()` and passes the result as `$activePirep` to the view.

### Displaying Live Status in Your Theme

You can display a LIVE badge next to a pilot's name anywhere in your theme by reading the `is_live` column:

```php
@if($user->is_live ?? false)
  <span class="badge bg-danger">
    <i class="fas fa-circle me-1" style="font-size:0.6rem;"></i> LIVE
  </span>
@endif
```

Or link to their stream:

```php
@if($user->is_live && $user->current_stream_url)
  <a href="{{ $user->current_stream_url }}" target="_blank" class="btn btn-sm btn-danger">
    Watch Live
  </a>
@endif
```

---

## Known Issues and Notes

* **Queue / Jobs**: The `CheckLiveStatusJob` is dispatched to the queue. If your phpVMS installation uses `QUEUE_CONNECTION=sync` (default), the job runs immediately in the request cycle. If using a real queue driver (Redis, database), ensure your queue worker is running.

* **Twitch Token**: The module requests a Twitch App Access Token on every PIREP state change (for the pilot that has Twitch configured). This is a server-to-server call and does not require the pilot to authorize. Tokens are not cached between requests in the current version.

* **OBS Overlay on HTTPS**: Both the phpVMS site and the overlay URL must be served over HTTPS if used in OBS on a secure context. OBS itself handles mixed content, but Streamlabs browser sources may block HTTP.

* **Passenger Panel with no ACARS data**: If the PIREP has no ACARS position data yet (pilot just started), the Passenger Panel will display `---` for all telemetry values until the first position update arrives.

---

## File Structure

```
modules/LiveStream/
├── Config/
│   └── config.php                          # Module config file
├── Console/                                # Artisan commands (if any)
├── Database/
│   └── migrations/
│       ├── ..._add_streaming_fields_to_users_table.php
│       ├── ..._create_passenger_interactions_table.php
│       └── ..._add_advanced_stream_fields_to_users.php
├── Http/
│   ├── Controllers/
│   │   ├── Frontend/
│   │   │   ├── ProfileController.php       # Stream settings page
│   │   │   ├── PassengerPanelController.php # Public passenger panel
│   │   │   └── OverlayController.php       # OBS overlay
│   │   ├── Admin/                          # Admin panel controllers
│   │   └── Api/                            # API controllers
│   └── Routes/
│       ├── web.php                         # All web routes
│       ├── admin.php                       # Admin routes
│       └── api.php                         # API routes
├── Jobs/
│   └── CheckLiveStatusJob.php              # Background job: checks Twitch/YT live status
├── Listeners/
│   ├── CheckLiveOnStateChange.php          # Fires on PirepStateChange event
│   ├── ProcessInteractionsOnPirepAccepted.php # Fires on PirepAccepted event
│   └── UpdateStreamSettings.php
├── Models/                                 # Eloquent models
├── Providers/
│   ├── AppServiceProvider.php
│   ├── EventServiceProvider.php            # Registers event listeners
│   └── RouteServiceProvider.php            # Registers module routes
├── Resources/
│   └── views/
│       ├── obs_overlay.blade.php           # OBS transparent overlay
│       ├── passenger_panel.blade.php       # Public passenger panel
│       └── profile_settings.blade.php      # Pilot stream settings
├── module.json                             # Module manifest
└── README.md                               # This file
```

---

## Português — Documentação Completa

### Visão Geral

O módulo **LiveStream** integra plataformas de streaming (Twitch, YouTube), notificações no Discord, telemetria ACARS em tempo real, um painel interativo de passageiros e um overlay transparente para OBS/Streamlabs no phpVMS 7.

**Funcionalidades:**
* Página de configurações de stream para pilotos (`/live/profile`)
* Deteção automática de estado live no Twitch e YouTube via API
* Notificação automática no Discord quando o piloto vai ao ar
* Overlay transparente para OBS (`/live/overlay/{pirep_id}`) com telemetria em tempo real
* Painel público de passageiros (`/live/passenger/{pirep_id}`) para espectadores
* Deteção inteligente de PIREP ativo — preenche automaticamente o URL do OBS com o ID real
* Sistema de interações de passageiros com atribuição de pontos bónus
* Badge LIVE visível no Live Map quando um piloto está em streaming
* Sem modificações ao core — módulo totalmente independente

### Instalação

1. Faça o upload da pasta `LiveStream` para `/modules` no seu phpVMS 7
2. Ative o módulo em admin → addons/modules
3. Vá a admin → dashboard (ou `/update`) para correr as migrações
4. Limpe a cache `application` em admin → maintenance

### Configuração de Rotas no Tema

Para adicionar um link na navbar ou no menu de perfil do seu tema:

```html
<a href="{{ route('livestream.profile.index') }}" class="nav-link">
  <i class="fas fa-satellite-dish me-1"></i> Definições de Stream
</a>
```

### Configurar o Overlay no OBS

1. Adicionar uma nova Source → **Browser**
2. URL: `https://seu-site.com/live/overlay/{pirep_id}` (substituir pelo ID real)
3. Largura: `1920`, Altura: `1080`
4. Marcar **"Page background color transparent"**

### Painel de Passageiros

Partilhe o link `https://seu-site.com/live/passenger/{pirep_id}` com os seus espectadores no chat da stream ou no Discord. A página atualiza automaticamente a telemetria a cada 10 segundos.

### Variáveis de Ambiente (`.env`)

```env
# Twitch API (opcional — necessário para deteção automática de estado live)
TWITCH_CLIENT_ID=
TWITCH_CLIENT_SECRET=

# YouTube API (opcional)
YOUTUBE_API_KEY=
```

### Integração com o Tema

Exibir um badge LIVE junto ao nome do piloto:

```php
@if($user->is_live ?? false)
  <span class="badge bg-danger">LIVE</span>
@endif
```

---

## License

MIT License — Free to use, modify and distribute with attribution.

---

*Module developed for FlyAzores Virtual — phpVMS v7*
