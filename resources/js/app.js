// 1. Importa il file bootstrap.js di Laravel
import "./bootstrap";

// 2. Importa jQuery
import $ from "jquery";
window.$ = window.jQuery = $;

// 3. Importa Bootstrap JavaScript
import "bootstrap";
// Se hai bisogno di accedere all'oggetto bootstrap globalmente per chiamate API dirette:
import * as bootstrapGlobal from "bootstrap"; // Importa come oggetto
window.bootstrap = bootstrapGlobal; // Assegna a window per accessibilità globale

// 4. Il tuo codice JavaScript personalizzato
document.addEventListener("DOMContentLoaded", function () {
    // --- INIZIO INIZIALIZZAZIONE TOOLTIP ---
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        // Ora che window.bootstrap è definito, possiamo usarlo con sicurezza
        if (window.bootstrap && window.bootstrap.Tooltip) {
            new window.bootstrap.Tooltip(tooltipTriggerEl);
        }
    });
    // --- FINE INIZIALIZZAZIONE TOOLTIP ---

    // --- INIZIO CODICE PER ARTICLE TOC (TABELLA CONTENUTI ARTICOLO) ---
    const sections = document.querySelectorAll("main section[id]");
    const tocLinks = document.querySelectorAll("#article-toc a");

    if (sections.length > 0 && tocLinks.length > 0) {
        function activateNavLink() {
            let index = sections.length;
            while (
                --index &&
                window.scrollY + 200 < sections[index].offsetTop // Aumentato leggermente l'offset
            ) {}

            tocLinks.forEach((link) =>
                link.classList.remove("active", "bg-light"),
            );
            if (tocLinks[index]) {
                tocLinks[index].classList.add("active", "bg-light");
            }
        }
        window.addEventListener("scroll", activateNavLink, { passive: true }); // Aggiunto passive: true
        activateNavLink();
    }
});
