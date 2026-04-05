# Harry Potter Characters Search

Interface de busca para personagens da API [HP-API](https://hp-api.onrender.com/). O projeto consome dados externos, normaliza do lado do servidor e exibe em cards com filtro instantâneo e troca dinâmica do fundo em vídeo por casa de Hogwarts.

## O que faz

O `index.php` busca todos os personagens da API via cURL, traduz os campos para português, gera fallbacks para dados ausentes e injeta o resultado no HTML como JSON seguro. O JavaScript lê os dados, renderiza os cards a partir de um template e aplica o filtro pela barra de busca. Cada card tem detalhes escondidos por padrão e se expande quando o usuário clica. Ao passar o mouse sobre um personagem, o vídeo de fundo troca para o da casa correspondente. A trilha sonora fica em mute até o usuário habilitar.

## Estrutura do projeto

```
.
├── config/
│   └── constants.php            # Endpoints, timeout, tentativas de retry
├── services/
│   ├── HpApiClient.php          # Consumo cURL com tratamento de erro
│   └── CharacterFormatter.php   # Normalização, tradução, fallbacks
├── includes/
│   ├── header.php               # Head, vídeo de fundo, áudio
│   ├── navbar.php               # Cabeçalho com marca e controle de áudio
│   ├── footer.php               # Rodapé com fonte de dados
│   └── card-template.php        # Template HTML dos cards
├── assets/
│   ├── css/app.css              # Layout, temas, responsivo
│   ├── js/app.js                # Renderização, busca, vídeo, áudio
│   └── media/
│       ├── img/                 # Vídeos por casa
│       │   ├── hogwarts.mp4
│       │   ├── gryffondor.mp4
│       │   ├── slytherin.mp4
│       │   ├── ravenclaw.mp4
│       │   └── hufflepuff.mp4
│       └── music.mp3
├── index.php                    # Entrada
├── .gitignore
└── README.md
```

Vídeos e trilha sonora estão no `.gitignore` por tamanho. O resto da aplicação funciona normalmente sem eles.

## Decisões técnicas

**PHP no servidor** — O cURL com `CURLOPT_TIMEOUT` e `CURLOPT_FOLLOWLOCATION` evita travar o carregamento quando a API (Render free tier) demora para responder. O `HpApiClient` retorna estrutura `['data' => array, 'error' => string]` em vez de arrays vazios, permitindo diferenciar falha de rede de resposta com conteúdo zero.

**Normalização em PHP** — O `CharacterFormatter` resolve tradução, formatação de varinha e fallback de imagem do lado do servidor. O navegador recebe dados prontos. Isso reduz a lógica do JavaScript à renderização e interação.

**Dados via JSON inline** — Os dados normalizados são serializados em `window.CHARACTERS_DATA`. Evita mais uma chamada ao servidor e mantém o filtro instantâneo responsivo.

**JavaScript modularizado** — Cinco blocos internos dentro da IIFE: `DataStore` (filtragem), `Renderer` (DOM), `SearchFilter` (busca e tecla Escape), `BackgroundVideo` (troca com transição) e `AudioController` (play/pause). Cada um trata de uma responsabilidade.

**Temas por casa** — Cores e transições definidas por classes CSS (`house-gryffindor`, `house-slytherin`, `house-ravenclaw`, `house-hufflepuff`). O JS apenas adiciona a classe baseado nos dados do personagem.

**Detalhes ocultos** — O `<details>` nativo do HTML expande e recolhe os metadados. Sem necessidade de JavaScript para isso, só para popular os valores.

## Funcionalidades

- Busca por nome, casa ou ator, com botão de limpar e tecla Escape
- Cards com retrato, casa traduzida, nome e ator visíveis, detalhes escondidos
- Troca automática do vídeo de fundo ao passar o mouse sobre um card
- Trilha sonora ativada pelo botão no cabeçalho, volume em 30% por padrão
- Estados visuais de carregamento, erro com botão de retry e busca sem resultados
- Layout responsivo: 1 coluna em mobile, 2 em tablet, 3 em desktop
- Fallback SVG para imagens não disponíveis na API

## Tecnologias

- PHP 8.0+ com cURL
- JavaScript Vanilla (IIFE)
- CSS custom properties + Bootstrap 5
- HTML semântico

## Como rodar

```
git clone https://github.com/AndersonC96/Harry-Potter-Characters-Search.git
cd Harry-Potter-Characters-Search

php -S localhost:8000

# acesse http://localhost:8000
```

Via XAMPP, basta copiar o conteúdo para `htdocs/` e acessar `http://localhost/Harry-Potter-Characters-Search/`. Não requer instalação de dependências nem banco de dados.

## Como adicionar os arquivos de mídia

Coloque os arquivos nesta estrutura:

```
assets/media/music.mp3
assets/media/img/hogwarts.mp4
assets/media/img/gryffondor.mp4
assets/media/img/slytherin.mp4
assets/media/img/ravenclaw.mp4
assets/media/img/hufflepuff.mp4
```

Sem eles a aplicação carrega normalmente. O fundo fica com a cor sólida e o botão de áudio fica disponível mas não tem conteúdo para reproduzir.

## Personalização

Cores das casas, caminhos de vídeo e transição de vídeo ficam no CSS. Volume padrão e controle de áudio ficam no `AudioController` em `assets/js/app.js`. Endpoints da API e timeout ficam em `config/constants.php`.

## Licença

Projeto para fins educacionais e de demonstração. Os dados dos personagens pertencem à API de terceiros. Consulte os termos de uso do HP-API antes de uso comercial.
