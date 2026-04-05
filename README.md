# Harry Potter Characters Search

Busca e listagem de personagens do universo Harry Potter, com interface temática e troca dinâmica de fundo em vídeo ao explorar as casas de Hogwarts.

## Sobre

O projeto consome a API pública [HP-API](https://hp-api.onrender.com/) para exibir cards dos personagens com nome, ator, casa e detalhes como varinha, espécies e patrono. A proposta une o consumo de dados externos com uma apresentação visual imersiva — troca de vídeo por casa, trilha sonora controlável e layout responsivo.

Os recursos de mídia (vídeos e trilha sonora) não estão incluídos no repositório por peso. Adicione-os seguindo a estrutura abaixo.

## Stack

- **PHP 8+** — consumo da API via cURL, normalização de dados e injeção segura no HTML
- **JavaScript Vanilla** — busca instantânea, renderização dinâmica e controle de mídia
- **CSS + Bootstrap 5** — layout responsivo, temas por casa e acabamentos

## Estrutura

```
.
├── config/           # Configuração e constantes
├── services/         # API client e normalização de dados
├── includes/         # Partial de HTML (header, navbar, footer, template)
├── assets/
│   ├── css/          # Estilos
│   ├── js/           # Scripts
│   └── media/
│       └── img/      # Vídeos por casa
└── index.php         # Ponto de entrada
```

## Como rodar

```bash
# 1. Clone
git clone https://github.com/AndersonC96/Harry-Potter-Characters-Search.git
cd Harry-Potter-Characters-Search

# 2. Servidor PHP builtin
php -S localhost:8000

# 3. Acesse
#    http://localhost:8000
```

> XAMPP: basta colocar na pasta `htdocs` e acessar por `http://localhost/Harry-Potter-Characters-Search/`.

## Adicionar mídia

Por motivo de tamanho, os arquivos de vídeo e áudio não entram no repositório. Para ter a experiência completa:

```
assets/media/
├── music.mp3
└── img/
    ├── hogwarts.mp4      # fundo padrão
    ├── gryffondor.mp4    # fundo Grifinória
    ├── slytherin.mp4     # fundo Sonserina
    ├── ravenclaw.mp4     # fundo Corvinal
    └── hufflepuff.mp4    # fundo Lufa-Lufa
```

Sem os arquivos, a busca e os cards funcionam normalmente — apenas a troca de fundo e áudio ficam indisponíveis.

## Personalização

- **Cores das casas em CSS:** variáveis `--house-*` em `assets/css/app.css`
- **Atributo por casa em CSS:** variáveis `--vid-*` (caminho dos vídeos)
- **Volume padrão do áudio:** ajuste em `AudioController` no `assets/js/app.js`

## Licença

Projeto para fins educacionais. Os dados dos personagens pertencem à API de terceiros. Consulte os termos de uso do HP-API.
