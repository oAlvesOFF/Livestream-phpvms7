# LiveStream Module for phpVMS 7

![phpVMS 7](https://img.shields.io/badge/phpVMS-v7-blue) ![Status](https://img.shields.io/badge/Status-Stable-brightgreen) ![License](https://img.shields.io/badge/License-MIT-orange)

🌍 **[English](#english)** | 🇵🇹 **[Português](#portugues)**

---

<a name="english"></a>
## 🌍 English Documentation

The **LiveStream** module is a premium, fully standalone addon for phpVMS 7 designed to integrate Twitch, YouTube, Discord, and vmsACARS telemetry into a stunning, interactive passenger panel and an OBS overlay. 

With a beautiful dark glassmorphism aesthetic, it allows virtual airline pilots to stream their flights like professionals.

### ✨ Features
- **Passenger Panel (`/live/passenger/{pirep_id}`)**: A beautiful, public-facing dashboard with glassmorphism UI where viewers can see real-time flight telemetry (Altitude, Speed, Heading, Vertical Speed, Distance).
- **OBS / Streamlabs Overlay (`/live/overlay/{pirep_id}`)**: A fully transparent overlay you can add to your broadcasting software. It automatically updates flight data every 8 seconds with dynamic glowing animations.
- **Smart PIREP Detection**: The pilot profile settings (`/live/profile`) automatically detect if the pilot has a flight *In Progress* via vmsACARS and automatically populates the `{pirep_id}` for the OBS URL.
- **Twitch & YouTube Integration**: Link your channel to show if you are live, pulling viewer count and stream title.
- **Discord Webhooks**: Automatically sends a webhook notification to your Discord server when you start a live flight.
- **Fully Independent**: Uses its own routing (`Modules/LiveStream/Http/Routes/web.php`) and requires zero modifications to the core phpVMS files.

### ⚙️ Installation
1. Upload the `LiveStream` folder to your `modules/` directory in your phpVMS 7 installation.
2. Go to your phpVMS Admin Panel -> **Modules**.
3. Find **LiveStream** and click **Enable**.
4. *(Optional)* If you are using a custom theme (e.g. ASA_THEME), ensure your theme has a link pointing to the route: `{{ route('livestream.profile.index') }}` so pilots can access the settings.

### 🎮 How to Use (For Pilots)
1. Book a flight and start it using **vmsACARS**. The flight must be *In Progress*.
2. Go to the **Stream Settings** page in your virtual airline profile (`/live/profile`).
3. The system will detect your active flight and automatically replace the ID for your OBS URL.
4. Copy the OBS URL and add it to OBS Studio as a **Browser Source** (Size: 1920x1080, check "Transparent background").
5. Share your **Passenger Panel** link with your viewers so they can follow your telemetry live!

---

<a name="portugues"></a>
## 🇵🇹 Documentação em Português

O módulo **LiveStream** é um addon premium e totalmente independente para o phpVMS 7 desenhado para integrar Twitch, YouTube, Discord e a telemetria do vmsACARS num painel interativo de passageiros e num overlay de OBS.

Com uma estética "dark glassmorphism", permite aos pilotos da companhia aérea virtual transmitirem os seus voos como profissionais.

### ✨ Funcionalidades
- **Painel do Passageiro (`/live/passenger/{pirep_id}`)**: Um dashboard público com design "glassmorphism" onde os espectadores podem ver a telemetria do voo em tempo real (Altitude, Velocidade, Rumo, Velocidade Vertical, Distância).
- **Overlay para OBS / Streamlabs (`/live/overlay/{pirep_id}`)**: Um overlay totalmente transparente que pode adicionar ao seu software de stream. Atualiza os dados do voo automaticamente a cada 8 segundos com animações dinâmicas ("glow").
- **Deteção Inteligente de PIREP**: A página de definições do piloto (`/live/profile`) deteta automaticamente se o piloto tem um voo *In Progress* via vmsACARS e preenche sozinho o `{pirep_id}` no URL do OBS.
- **Integração com Twitch e YouTube**: Associe o seu canal para mostrar se está em direto, exibindo o número de viewers e o título da stream.
- **Webhooks do Discord**: Envia automaticamente uma notificação para o seu servidor de Discord quando inicia um voo ao vivo.
- **Totalmente Independente**: Utiliza o seu próprio sistema de rotas (`Modules/LiveStream/Http/Routes/web.php`) e não requer qualquer modificação aos ficheiros originais "Core" do phpVMS.

### ⚙️ Instalação
1. Faça o upload da pasta `LiveStream` para dentro do diretório `modules/` na sua instalação do phpVMS 7.
2. Vá ao Painel de Administração do phpVMS -> **Modules**.
3. Encontre o módulo **LiveStream** e clique em **Ativar (Enable)**.
4. *(Opcional)* Se estiver a usar um tema personalizado (ex: ASA_THEME), certifique-se de que o seu tema tem um link a apontar para a rota: `{{ route('livestream.profile.index') }}` para que os pilotos possam aceder às definições.

### 🎮 Como Utilizar (Para Pilotos)
1. Reserve um voo e inicie-o utilizando o **vmsACARS**. O voo tem de estar *In Progress*.
2. Vá à página de **Definições de Stream** no seu perfil da companhia (`/live/profile`).
3. O sistema irá detetar o seu voo ativo e substituirá automaticamente o ID no link do seu OBS.
4. Copie o URL do OBS e adicione-o ao OBS Studio como uma **Browser Source** (Tamanho: 1920x1080, marque a caixa "Fundo Transparente").
5. Partilhe o link do **Painel do Passageiro** com os seus espectadores para que possam acompanhar a sua telemetria ao vivo!
