<div align="center">
  
  <img src="https://img.shields.io/badge/phpVMS-v7-067ec1?style=for-the-badge&logo=php" alt="phpVMS 7" />
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Twitch-9146FF?style=for-the-badge&logo=twitch&logoColor=white" alt="Twitch" />
  <img src="https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white" alt="YouTube" />

  <br><br>

  <h1>📡 phpVMS 7 LiveStream Module</h1>
  <p><b>O módulo definitivo para integrar as streams dos seus pilotos diretamente no ecossistema da sua Virtual Airline.</b></p>
  
</div>

---

## 🚀 Visão Geral do Módulo

O **LiveStream Module** transforma a maneira como os pilotos interagem com a sua comunidade. Construído especificamente para o **phpVMS 7**, este módulo conecta o backend de ACARS do sistema à API das principais plataformas de streaming.

Sempre que um piloto inicia um voo no vmsACARS, o sistema automaticamente verifica se o canal dele está ao vivo. Caso positivo, o site ganha um recurso interativo onde visitantes e fãs podem assistir ao voo, ver dados de telemetria em tempo real e enviar doações de "serviço de bordo" — o que no final gera dinheiro virtual/bônus no perfil do comandante!

---

## ✨ Principais Funcionalidades

- **📺 Streamer Mode Inteligente**: Ao mudar o status do voo (PIREP) para `IN_PROGRESS`, um *Job Assíncrono* valida o status do streaming e altera a visibilidade do piloto em todo o site.
- **🛫 Passenger Panel (Painel do Passageiro)**: Rota pública gerada magicamente (`/live/passenger/{pirep_id}`). Espectadores assistem à stream via IFRAME nativo junto aos instrumentos do voo.
- **📊 Telemetria via AJAX**: Componente Livewire/AJAX acoplado para renderizar _Altitude_, _Velocidade_, _Proa_ e _Milhas Restantes_ sem atualizar a página.
- **🍔 O Meta-Game de Gorjetas**: Espectadores usam o menu de bordo interativo para pagar (virtualmente) cafés, sanduíches ou jantares aos pilotos.
- **💰 Integração Financeira Nativa**: Usando a classe `JournalService` do phpVMS, as interações interativas no chat dão recompensas diretas em dólares $ no fechamento de voo para o piloto!

---

## 🛠 Passo a Passo da Instalação

### 1. Copiando os Ficheiros
Faça o download do repositório ou faça um git clone. Coloque toda a pasta `LiveStream` dentro da pasta raiz de módulos do seu phpVMS:
```text
phpvms_root/
└── modules/
    └── LiveStream/
```

### 2. Recarregando o Laravel
Entre no terminal, navegue até a pasta raiz do seu phpVMS 7 e recarregue os pacotes:
```bash
composer dump-autoload
php artisan cache:clear
php artisan route:clear
```

### 3. Criando as Tabelas (Migrations)
Execute o comando abaixo para injetar as colunas de Streaming na tabela `users` e criar a tabela nativa de interações:
```bash
php artisan migrate --path=modules/LiveStream/Database/Migrations
```

### 4. Ativando o Módulo
Garanta que o módulo está listado como ativo. Se você usa o módulo via `modules_statuses.json` na raiz do sistema, certifique-se de que está definido assim:
```json
{
    "LiveStream": true
}
```

---

## ⚙️ Documentação Detalhada & Configuração

### 1. Configurando a Verificação de API (Twitch/YouTube)
Por padrão, no ambiente de desenvolvimento, o sistema força a variável `is_live = true` para facilitar os testes se o campo Twitch for preenchido. 
Para um cenário em **PRODUÇÃO**, abra o arquivo:
📄 `modules/LiveStream/Jobs/CheckLiveStatusJob.php`

**Twitch:**
Descomente o código do `Http::withHeaders()` da Twitch e adicione no seu arquivo `.env`:
```env
TWITCH_CLIENT_ID="seu_client_id_gerado_no_twitch_dev"
TWITCH_ACCESS_TOKEN="seu_token_de_acesso"
```

**YouTube:**
Descomente a condicional do YouTube e adicione no `.env`:
```env
YOUTUBE_API_KEY="sua_chave_gerada_no_google_console"
```

### 2. Injetando no Mapa de Voos (Modificação de View)
O Painel Principal do phpVMS 7 exibe os voos ativos. Siga o guia abaixo para integrar o botão no seu respectivo tema (ex: **Stisla** ou **Spark**).

Abra o arquivo de Live Map do seu layout. Geralmente fica em:
📄 `resources/views/layouts/<seu-tema>/widgets/live_map.blade.php`

Localize a estrutura `<style>` e adicione nossa animação de pulso para chamar a atenção do público:
```css
@keyframes pulse {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
  70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
```

Vá até o bloco html que renderiza o painel do avião ao ser clicado (`#map-info-box`) e adicione este código **no final** do info-box:

```html
{{-- Live Stream Panel Link --}}
<div class="row mt-3 pt-3 border-top" rv-show="pirep.user.is_live">
    <div class="col-12 text-center">
        <a rv-href="pirep.id | prepend '/live/passenger/'" target="_blank" class="btn btn-sm btn-danger pulse" style="border-radius: 50px; font-weight: bold; animation: pulse 2s infinite;">
            <i class="fas fa-video me-2"></i> LIVE AGORA - Entrar como Passageiro
        </a>
    </div>
</div>
```

---

## 🛡️ Entendendo a Segurança (Rate-Limiting)

Para evitar que bots do servidor ou usuários mal intencionados causem inflação na economia da VA ao clicar no "Menu de Bordo" infinitas vezes:
- A rota `/live/passenger/{pirep_id}/interact` checa instantaneamente o **Endereço IP** do espectador.
- Há uma restrição fixa de **15 MINUTOS (Cooldown)** entre cada interação usando a base de dados em MySQL. 
- O sistema bloqueia a ação via AJAX e exibe o tempo cronometrado restante no Toast se alguém tentar dar bypass.

---

## 🙋‍♂️ Perguntas Comuns / Troubleshooting

**P: Os dados (telemetria) do avião não mudam na página do passageiro!**
R: A telemetria depende do vmsACARS enviar os dados atualizados para a sua DB. Verifique se o seu tracking time no ACARS não está configurado para um intervalo longo (ex: maior que 2 minutos).

**P: O dinheiro das doações não está caindo na conta dos pilotos!**
R: A função está atrelada ao `PirepAccepted`. Os fundos só são liberados de forma consolidada no momento que o administrador ou o sistema automático da VA aceita (Approve) o relatório daquele voo. 

---

## 👨‍💻 Créditos & Autoria

**Criado e Desenvolvido por: Rui Alves (ASA0001)**
🌍 Visite nosso site oficial e voe conosco: 🔗 [https://flyazoresvirtual.com](https://flyazoresvirtual.com)

> *Software desenvolvido do zero para revolucionar o engajamento e trazer uma gamificação de última geração para o cenário de Aviação Virtual via phpVMS 7.*

---

<div align="center">
  <p>Desenvolvido orgulhosamente para o <b>phpVMS 7</b> | Todos os Direitos Reservados</p>
</div>
