<div class="row g-4">
    {{-- Colonna Principale (Contenuto Fisso) --}}
    {{-- Colonna Principale (Contenuto Variabile) --}}
    <div class="col-lg-8">
        {{ $slot }} {{-- Lo slot di default conterrà il contenuto principale --}}
    </div>

    {{-- Colonna Sidebar (Contenuto Fisso) --}}
    <aside class="col-lg-4">
        <div class="sidebar-content sticky-top pt-4">
            <div class="row"> {{-- Manteniamo la struttura interna della sidebar come l'hai definita --}}
                <div class="d-flex flex-column gap-4">
                    <section>
                        <x-shared.newsletter-card />
                    </section>
                    <section>
                        <x-shared.service-promo-card />
                    </section>
                </div>
            </div>
        </div>
    </aside>
</div>
