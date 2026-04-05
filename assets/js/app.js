/**
 * Harry Potter Characters Search — app.js
 *
 * Modules:
 *   - DataStore       :: raw characters + helpers
 *   - Renderer        :: builds DOM from template
 *   - SearchFilter    :: instant filtering
 *   - BackgroundVideo :: house-based video swap
 *   - AudioController :: play/pause / icon toggle
 *   - Bootstrap        :: wiring
 */

(function () {
    "use strict";

    /* ---- DOM refs -------------------------------------------------- */
    const searchInput = document.getElementById("search-input");
    const searchClear = document.getElementById("search-clear");
    const listEl = document.getElementById("characters-list");
    const loadingState = document.getElementById("loading-state");
    const errorState = document.getElementById("error-state");
    const emptyState = document.getElementById("empty-state");
    const template = document.getElementById("characterCardTemplate");
    const bgVideo = document.getElementById("bg-video");
    const audioEl = document.getElementById("bg-audio");
    const audioBtn = document.getElementById("audio-toggle");

    /* ---- DataStore ------------------------------------------------- */
    const DataStore = {
        raw: [],

        init(raw) {
            this.raw = Array.isArray(raw) ? raw : [];
        },

        query(term) {
            if (!term) return this.raw;
            const q = term.toLowerCase();
            return this.raw.filter((c) => {
                return (
                    (c.name || "").toLowerCase().includes(q) ||
                    (c.house.name || "").toLowerCase().includes(q) ||
                    (c.house.key || "").includes(q) ||
                    (c.actor || "").toLowerCase().includes(q)
                );
            });
        },
    };

    /* ---- Renderer -------------------------------------------------- */
    const Renderer = {
        buildCard(character) {
            if (!template || !template.content) return null;
            const clone = template.content.cloneNode(true);
            const card = clone.querySelector(".character-card");

            // Name, actor
            setText(clone, ".card-name", character.name);
            setText(clone, ".card-actor", character.actor);
            setText(clone, ".house-pill", character.house.name || "Desconhecida");

            // Portrait
            const img = clone.querySelector(".portrait-img");
            if (img && character.image) {
                img.src = character.image;
                img.alt = "Retrato de " + character.name;
            }

            // Details
            setText(clone, ".detail-species", character.species);
            setText(clone, ".detail-gender", character.gender);
            setText(clone, ".detail-house", character.house.name);
            setText(clone, ".detail-birth", character.dateOfBirth);
            setText(clone, ".detail-ancestry", character.ancestry);
            setText(clone, ".detail-hair", character.hairColour);
            setText(clone, ".detail-eyes", character.eyeColour);
            setText(clone, ".detail-wand", character.wand);
            setText(clone, ".detail-patronus", character.patronus);

            // House class
            if (character.house.key) {
                card.classList.add(character.house.key);
            }

            // Background video swap on hover
            if (card) {
                card.dataset.house = character.house.key || "";
            }

            return clone;
        },

        render(list) {
            // Remove old cards only, keep template
            const old = listEl.querySelectorAll(".col");
            old.forEach((el) => el.remove());

            if (!list.length) {
                return 0;
            }

            const frag = document.createDocumentFragment();
            list.forEach((char) => {
                const card = this.buildCard(char);
                if (card) frag.appendChild(card);
            });
            listEl.appendChild(frag);
            return list.length;
        },
    };

    /* ---- SearchFilter ---------------------------------------------- */
    const SearchFilter = {
        active: false,

        bind(onFilter) {
            if (!searchInput) return;

            searchInput.addEventListener("input", () => {
                const term = searchInput.value.trim();
                this.active = term.length > 0;
                onFilter(term);
                toggleSearchClear(term.length > 0);
            });

            searchInput.addEventListener("keydown", (e) => {
                if (e.key === "Escape") {
                    searchInput.value = "";
                    this.active = false;
                    onFilter("");
                    toggleSearchClear(false);
                    searchInput.blur();
                }
            });

            if (searchClear) {
                searchClear.addEventListener("click", () => {
                    searchInput.value = "";
                    this.active = false;
                    onFilter("");
                    toggleSearchClear(false);
                    searchInput.focus();
                });
            }
        },
    };

    /* ---- BackgroundVideo ------------------------------------------- */
    const BackgroundVideo = {
        current: "",
        timer: null,

        videos: {},

        init() {
            this.videos = {
                'house-gryffindor': getCSS("--vid-gryffindor"),
                'house-slytherin': getCSS("--vid-slytherin"),
                'house-ravenclaw': getCSS("--vid-ravenclaw"),
                'house-hufflepuff': getCSS("--vid-hufflepuff"),
            };
            this.defaultSrc = getCSS("--vid-default");
            if (bgVideo) {
                this.swap(this.defaultSrc);
            }
        },

        bindCards() {
            listEl.addEventListener("mouseover", (e) => {
                const card = e.target.closest(".character-card");
                if (!card) return;
                const house = card.dataset.house;
                const src = house && this.videos[house] ? this.videos[house] : null;
                if (src) this.swap(src);
            });

            listEl.addEventListener("mouseout", (e) => {
                const card = e.target.closest(".character-card");
                if (!card) return;
                // Check if we actually left the card
                const related = e.relatedTarget;
                if (related && card.contains(related)) return;
                this.swap(this.defaultSrc);
            });
        },

        swap(src) {
            if (!bgVideo || !src) return;
            if (this.current === src) return;
            this.current = src;

            bgVideo.style.opacity = "0";
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                bgVideo.src = src;
                bgVideo.load();
                bgVideo.play().catch(() => {});
                bgVideo.style.opacity = "1";
            }, 180);
        },
    };

    /* ---- AudioController ------------------------------------------- */
    const AudioController = {
        playing: false,

        init() {
            if (!audioEl || !audioBtn) return;

            // Start muted/off
            audioEl.volume = 0.3;

            audioBtn.addEventListener("click", () => {
                if (this.playing) {
                    audioEl.pause();
                    this.playing = false;
                    audioBtn.setAttribute("aria-label", "Ativar trilha sonora");
                    audioBtn.querySelector(".icon-audio-off").style.display = "";
                    audioBtn.querySelector(".icon-audio-on").style.display = "none";
                } else {
                    audioEl.play().catch(() => {});
                    this.playing = true;
                    audioBtn.setAttribute("aria-label", "Desativar trilha sonora");
                    audioBtn.querySelector(".icon-audio-off").style.display = "none";
                    audioBtn.querySelector(".icon-audio-on").style.display = "";
                }
            });
        },
    };

    /* ---- Bootstrap ------------------------------------------------- */
    function init() {
        BackgroundVideo.init();
        AudioController.init();

        const rawData = window.CHARACTERS_DATA;
        if (!rawData || !rawData.length) {
            // No data — show error or empty
            showState("loading", false);
            return;
        }

        DataStore.init(rawData);
        BackgroundVideo.bindCards();

        SearchFilter.bind((term) => {
            const filtered = DataStore.query(term);
            const count = Renderer.render(filtered);
            showList(count > 0);
            showEmpty(count === 0 && term.length > 0);
        });

        Renderer.render(DataStore.raw);
        showState("loading", false);
        showState("error", false);
    }

    /* ---- State helpers --------------------------------------------- */
    function showState(name, visible) {
        const map = {
            loading: loadingState,
            error: errorState,
        };
        if (!map[name]) return;
        map[name].style.display = visible ? "" : "none";
    }

    function showList(visible) {
        listEl.style.display = visible ? "" : "none";
    }

    function showEmpty(visible) {
        emptyState.style.display = visible ? "" : "none";
    }

    function toggleSearchClear(visible) {
        if (!searchClear) return;
        if (visible) {
            searchClear.style.display = "flex";
            searchClear.classList.add("visible");
        } else {
            searchClear.style.display = "none";
            searchClear.classList.remove("visible");
        }
    }

    /* ---- Utils ----------------------------------------------------- */
    function setText(root, selector, value) {
        const el = root.querySelector(selector);
        if (el) el.textContent = value || "Nao informado";
    }

    function getCSS(prop) {
        return getComputedStyle(document.documentElement)
            .getPropertyValue(prop)
            .trim();
    }

    /* ---- Kick off -------------------------------------------------- */
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
