{{-- resources/views/components/layout/footer.blade.php (Versione Originale Fornita) --}}
<footer class="footer-custom-background text-light pt-5 pb-4 mt-auto border-top">
    <div class="container text-center text-md-start">
        <div class="row gy-4">
            <!-- Colonna Logo e Descrizione -->
            <div class="col-md-4 col-lg-3 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}" alt="PIMEL Logo" height="30"
                        class="me-2">PIMEL
                </h6>
            </div>

            <!-- Colonna Link Utili -->
            <div class="col-md-2 col-lg-2 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">
                    Link Utili
                </h6>
                <p><a href="{{ route('home') }}" class="text-reset text-decoration-none">Home</a></p>
                <p><a href="{{ route('blog.index') }}" class="text-reset text-decoration-none">Blog</a></p>
                <p><a href="{{ route('servizi.index') }}" class="text-reset text-decoration-none">Servizi</a></p>
                <p><a href="{{ route('pages.about') }}" class="text-reset text-decoration-none">Chi Sono</a></p>
            </div>

            <!-- Colonna Contatti -->
            <div class="col-md-3 col-lg-2 mx-auto mb-4">
                <h6 class="text-uppercase fw-bold mb-4">
                    Contatti
                </h6>
                <p><a href="{{ route('contatti.form') }}" class="text-reset text-decoration-none">Contattami</a></p>
                <p><a href="mailto:info@pimel.it" class="text-reset text-decoration-none">info@pimel.it</a></p>
            </div>

            <!-- Colonna Social -->
            <div class="col-md-3 col-lg-3 mx-auto mb-md-0 mb-4">
                <h6 class="text-uppercase fw-bold mb-4">Seguimi</h6>
                <a href="#" class="me-4 text-reset text-decoration-none" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="me-4 text-reset text-decoration-none" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="me-4 text-reset text-decoration-none" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="me-4 text-reset text-decoration-none" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Telegram">
                    <i class="fab fa-telegram-plane"></i>
                </a>
            </div>
        </div>
    </div>
</footer>
