<?php

declare(strict_types=1);

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/services/HpApiClient.php';
require_once __DIR__ . '/services/CharacterFormatter.php';

$pageTitle = 'Harry Potter Characters';

$client = new HpApiClient(HP_API_URL, API_TIMEOUT_SEC);
$result = $client->fetchCharacters();

$characters = [];
$apiError = null;
if ($result['error'] !== null) {
    $apiError = $result['error'];
} else {
    foreach ($result['data'] as $raw) {
        $characters[] = CharacterFormatter::normalize($raw);
    }
}

/**
 * Resolve asset path for subdirectory serving.
 */
function assetPath(string $type, string $file): string
{
    $paths = [
        'css' => 'assets/css/' . $file,
        'js' => 'assets/js/' . $file,
        'media' => 'assets/media/' . $file,
    ];
    return $paths[$type] ?? $file;
}

?>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- Audio toggle moved to navbar -->
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<!-- Search Section -->
<section class="search-section" aria-label="Busca de personagens">
    <div class="container">
        <div class="search-wrap">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
            <input
                type="search"
                id="search-input"
                class="search-field"
                placeholder="Buscar personagem, casa ou ator..."
                autocomplete="off"
                aria-label="Buscar personagens"
            >
            <button class="search-clear" id="search-clear" type="button" aria-label="Limpar busca" style="display:none">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="main-content">
    <div class="container">

        <?php $hasData = ($apiError === null && $characters !== []); ?>

        <!-- Loading State -->
        <div id="loading-state" class="state-message"<?php echo $hasData ? ' style="display:none"' : ''; ?>>
            <div class="state-spinner" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M10.28 1.259a.5.5 0 0 1 .427.562c-.178 1.38-.834 3.278-2.64 4.967a.5.5 0 0 1-.394.132c-2.229-.196-4.806-.795-5.974-2.374a.5.5 0 0 1 .774-.634c.917 1.24 3.073 1.786 5.178 1.623C9.14 3.683 9.696 1.83 9.696 1.75a.5.5 0 0 1 .584-.491z" opacity=".5"/>
                    <path d="M7.995 16a5 5 0 0 0 4.574-3.009.5.5 0 0 0-.492-.707l-.703.004a.5.5 0 0 0-.423.262 4 4 0 0 1-3.436 1.9 4 4 0 0 1-2.714-1.124.5.5 0 0 0-.77.634A5 5 0 0 0 7.995 16z"/>
                </svg>
            </div>
            <p>Carregando personagens...</p>
        </div>

        <!-- Error State -->
        <div id="error-state" class="state-message"<?php echo $apiError === null ? ' style="display:none"' : ''; ?>>
            <svg class="state-icon" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <p id="error-text"><?php echo htmlspecialchars($apiError ?? ''); ?></p>
            <button class="btn btn-retry" onclick="window.location.reload()">Tentar novamente</button>
        </div>

        <!-- No Results State -->
        <div id="empty-state" class="state-message" style="display:none">
            <svg class="state-icon" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
            <p>Nenhum bruxo encontrado para essa busca.</p>
        </div>

        <!-- Character List -->
        <div id="characters-list" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3"<?php echo $hasData ? '' : ' style="display:none"'; ?>>

            <!-- Template -->
            <?php require_once __DIR__ . '/includes/card-template.php'; ?>

            <?php if (empty($apiError) && $characters !== []): ?>
            <script>
                window.CHARACTERS_DATA = <?php echo json_encode($characters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
            </script>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?php echo assetPath('js', 'app.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
