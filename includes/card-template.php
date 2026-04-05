<script type="text/template" id="characterCardTemplate">
    <div class="col">
        <article class="card character-card h-100" tabindex="0">
            <div class="card-portrait">
                <img class="portrait-img" src="" alt="" loading="lazy">
            </div>
            <div class="card-content">
                <header class="card-head">
                    <div class="card-head-main">
                        <h2 class="card-name"></h2>
                        <p class="card-actor"></p>
                    </div>
                    <span class="house-pill"></span>
                </header>

                <details class="card-details">
                    <summary class="card-details-toggle" tabindex="0">
                        <span class="toggle-text">Ver detalhes</span>
                        <span class="toggle-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                    </summary>
                    <div class="card-details-body">
                        <dl class="details-list">
                            <div class="details-item">
                                <dt>Especie</dt>
                                <dd class="detail-species"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Genero</dt>
                                <dd class="detail-gender"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Casa</dt>
                                <dd class="detail-house"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Nascimento</dt>
                                <dd class="detail-birth"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Ancestralidade</dt>
                                <dd class="detail-ancestry"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Cabelo</dt>
                                <dd class="detail-hair"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Olhos</dt>
                                <dd class="detail-eyes"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Varinha</dt>
                                <dd class="detail-wand"></dd>
                            </div>
                            <div class="details-item">
                                <dt>Patrono</dt>
                                <dd class="detail-patronus"></dd>
                            </div>
                        </dl>
                    </div>
                </details>
            </div>
        </article>
    </div>
</script>
