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
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
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
                link.classList.remove("active", "bg-light")
            );
            if (tocLinks[index]) {
                tocLinks[index].classList.add("active", "bg-light");
            }
        }
        window.addEventListener("scroll", activateNavLink, { passive: true }); // Aggiunto passive: true
        activateNavLink();
    }
    // --- FINE CODICE PER ARTICLE TOC ---

    // --- INIZIO GESTIONE FORM DI CONTATTO (SIMULAZIONE) ---
    const contactForm = document.getElementById("contactForm");
    const formMessage = document.getElementById("formMessage");

    if (contactForm && formMessage) {
        contactForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const nameInput = document.getElementById("contactName");
            const emailInput = document.getElementById("contactEmail");
            const messageInput = document.getElementById("contactMessage");
            const privacyCheckbox = document.getElementById("privacyPolicy");

            const name = nameInput ? nameInput.value : "";
            const email = emailInput ? emailInput.value : "";
            const message = messageInput ? messageInput.value : "";
            const privacy = privacyCheckbox ? privacyCheckbox.checked : false;

            if (
                name.trim() === "" ||
                email.trim() === "" ||
                message.trim() === "" ||
                !privacy
            ) {
                formMessage.innerHTML =
                    '<div class="alert alert-danger" role="alert">Per favore, compila tutti i campi obbligatori e accetta la privacy policy.</div>';
                return;
            }

            formMessage.innerHTML =
                '<div class="alert alert-info" role="alert">Invio del messaggio in corso... (Simulazione JS)</div>';
            setTimeout(() => {
                formMessage.innerHTML =
                    '<div class="alert alert-success" role="alert">Messaggio inviato con successo! (Simulazione JS)</div>';
                contactForm.reset();
            }, 2000);
        });
    }
    // --- FINE GESTIONE FORM DI CONTATTO ---

    // --- INIZIO GESTIONE TAB ARTICOLI BLOG (SIMULAZIONE) ---
    const sortTabs = document.querySelectorAll("#sortTabs .nav-link");
    if (sortTabs.length > 0) {
        sortTabs.forEach((tab) => {
            tab.addEventListener("click", function (event) {
                event.preventDefault();
                sortTabs.forEach((t) => t.classList.remove("active"));
                this.classList.add("active");
            });
        });
    }
    // --- FINE GESTIONE TAB ARTICOLI BLOG ---
}); // Fine DOMContentLoaded
