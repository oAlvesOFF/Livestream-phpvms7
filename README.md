# LiveStream Module for phpVMS v7 ✈️📡

*(Scroll down for English version)*

---

## 🇵🇹 Versão em Português

Bem-vindo ao **LiveStream Module** para o phpVMS v7! Este módulo transforma a tua Virtual Airline adicionando uma integração profunda com plataformas de streaming (Twitch e YouTube), permitindo aos pilotos transmitir os seus voos com funcionalidades avançadas.

### 🌟 Funcionalidades Principais

- **OBS / Streamlabs Overlay:** Um URL dedicado (`/live/overlay/{pirep_id}`) com fundo transparente que mostra a telemetria ao vivo do voo (Altitude, Velocidade, Rumo, Fase de Voo) diretamente no OBS, com atualizações automáticas via vmsACARS.
- **Integração Twitch & YouTube:** Os pilotos podem adicionar o seu canal nas definições de perfil. O sistema mostra quando estão LIVE.
- **Notificações Automáticas no Discord:** Envia um webhook para o Discord da VA quando um piloto entra em voo ao vivo.
- **Painel do Passageiro Interativo:** Um painel público (`/live/passenger/{pirep_id}`) onde os visitantes podem assistir à stream e "interagir" com o piloto oferecendo snacks, cafés, jantares (ou reclamações!), com um sistema de pontuação.
- **Design Premium Glassmorphism:** O módulo inclui um design escuro e imersivo, feito à medida para encaixar perfeitamente em temas modernos como o ASA_THEME.
- **Controlador Core Autónomo:** As rotas principais do módulo estão embebidas no RouteServiceProvider do Laravel para garantir 100% de funcionamento e impedir que erros de cache do phpVMS escondam o menu.

### 🚀 Instalação (Sem Acesso SSH / Shared Host)

Se usas um alojamento partilhado (cPanel, Plesk, etc.) e não tens acesso ao terminal, podes instalar o módulo em 3 passos simples:

1. **Upload dos Ficheiros:**
   Copia todo o conteúdo desta pasta (`app/`, `modules/`, `public/`, `resources/`) para a raiz da instalação do teu phpVMS (normalmente dentro da pasta `public_html`). Substitui os ficheiros se for pedido.
   *Nota: O módulo inclui modificações seguras ao `RouteServiceProvider.php` para garantir que as rotas estão sempre ativas.*

2. **Registar e Ativar na Base de Dados:**
   Abre o teu navegador e acede ao seguinte link para registar o módulo automaticamente e limpar as caches:
   `https://[o-teu-site.com]/register_livestream.php`

3. **Verificação Final:**
   Vai ao teu perfil no phpVMS. Deverás ver agora o botão "Stream Settings" na barra de navegação superior (no dropdown do perfil) ou na sidebar.

---

## 🇬🇧 English Version

Welcome to the **LiveStream Module** for phpVMS v7! This module upgrades your Virtual Airline by adding deep integration with streaming platforms (Twitch and YouTube), empowering your pilots to broadcast their flights with advanced tools.

### 🌟 Main Features

- **OBS / Streamlabs Overlay:** A dedicated transparent browser source URL (`/live/overlay/{pirep_id}`) that displays live flight telemetry (Altitude, Speed, Heading, Flight Phase) straight into OBS, updating automatically via vmsACARS.
- **Twitch & YouTube Integration:** Pilots can bind their channels in their profile settings. The VA will know when they are broadcasting.
- **Discord Automated Webhooks:** Sends a rich notification to your VA's Discord server whenever a pilot starts a live flight.
- **Interactive Passenger Panel:** A public-facing panel (`/live/passenger/{pirep_id}`) where visitors can watch the embedded stream and "interact" with the pilot by offering snacks, coffee, meals, or complaints, complete with a scoring system.
- **Premium Glassmorphism Design:** Dark, immersive aesthetics tailored to seamlessly integrate with modern templates like ASA_THEME.
- **Standalone Core Controller:** Critical routes are injected into Laravel's core RouteServiceProvider to guarantee 100% uptime, preventing phpVMS cache quirks from hiding the module menu.

### 🚀 Installation (No SSH / Shared Hosting)

If you are on a shared host (cPanel, Plesk) and don't have SSH access, installation is just 3 easy steps:

1. **Upload the Files:**
   Copy all the contents of this folder (`app/`, `modules/`, `public/`, `resources/`) to your phpVMS root directory (usually `public_html`). Overwrite files if prompted.
   *Note: This includes a safe, permanent modification to `RouteServiceProvider.php` to ensure routes never fail to load.*

2. **Register & Activate via Database:**
   Open your web browser and navigate to the following link to automatically insert the module into the database and clear all caches:
   `https://[your-domain.com]/register_livestream.php`

3. **Final Check:**
   Go to your profile on your phpVMS frontend. You should now see the "Stream Settings" button in your top navbar dropdown or sidebar.
