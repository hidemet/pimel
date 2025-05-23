{{-- resources/views/components/layout/header.blade.php --}}
<header>
    <nav class="navbar navbar-expand-md py-3 ">
        <div class="container"> {{-- Il container che limita a 1140px --}}

            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}" alt="PIMEL Logo" height="35">
            </a>

            {{-- SEZIONE UTENTE (Spostata qui per essere sempre visibile) --}}
            {{-- Sarà allineata a destra su schermi grandi (md+) usando ms-auto sulla ul principale --}}
            {{-- Su mobile (sotto md), l'ordine nel DOM la farà apparire dopo il brand e prima del toggler se il toggler è messo dopo --}}
            {{-- O possiamo usare flex order per un controllo più fine --}}

            <div class="d-flex align-items-center ms-auto order-md-3"> {{-- Contenitore per Utente e Toggler, order-md-3 per metterlo dopo il menu su desktop --}}

                {{-- ICONA/LINK UTENTE --}}
                @guest
                    <a class="nav-link text-custom-navbar p-2" href="{{ route('login') }}" title="Accedi o Registrati">
                        <span class="material-symbols-outlined align-middle fs-4">person</span> {{-- Icona sempre visibile, fs-4 per dimensione --}}
                    </a>
                @else
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center p-2" href="#"
                            id="userDropdownMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined fs-4 me-md-1">account_circle</span> {{-- Icona sempre visibile, fs-4 --}}
                            <span class="d-none d-md-inline-block small">{{ Str::limit(Auth::user()->name, 15) }}</span>
                            {{-- Nome solo da MD in su --}}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2" aria-labelledby="userDropdownMobile">
                            <li class="px-3 py-2">
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <div class="small text-muted">{{ Auth::user()->email }}</div>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            @if (Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item small" href="{{ route('admin.dashboard') }}">
                                        <span
                                            class="material-symbols-outlined align-middle me-2">admin_panel_settings</span>Pannello
                                        Admin
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item small" href="{{ route('profile.edit') }}">
                                    <span class="material-symbols-outlined align-middle me-2">manage_accounts</span>Mio
                                    Profilo
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item small">
                                        <span class="material-symbols-outlined align-middle me-2">logout</span>Esci
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest

                <!-- Bottone Hamburger per Mobile (ora DOPO l'icona utente nel DOM per mobile) -->
                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavContent" aria-controls="navbarNavContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>


            <!-- Contenuto Collassabile: Menu e (precedentemente) Autenticazione -->
            <div class="collapse navbar-collapse order-md-2" id="navbarNavContent"> {{-- order-md-2 per posizionarlo tra logo e utente/toggler su desktop --}}
                <ul class="navbar-nav ms-auto me-md-3 align-items-lg-center"> {{-- ms-auto per allineare a destra, me-md-3 per spazio dall'utente --}}
                    {{-- Link di Navigazione Principali --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blog.index') || request()->routeIs('blog.show') ? 'active' : '' }}"
                            href="{{ route('blog.index') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('servizi.index') ? 'active' : '' }}"
                            href="{{ route('servizi.index') }}">Servizi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pages.about') ? 'active' : '' }}"
                            href="{{ route('pages.about') }}">Chi Sono</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('newsletter.form') ? 'active' : '' }}"
                            href="{{ route('newsletter.form') }}">Newsletter</a>
                    </li>

                    {{-- Pulsante Contattami (Gestione Responsive) --}}
                    <li class="nav-item d-lg-none mt-2 mb-2"> {{-- Visibile solo sotto LG (quando il menu è più compatto o collassato) --}}
                        <a href="{{ route('contatti.form') }}"
                            class="btn btn-ct w-100 {{ request()->routeIs('contatti.form') ? 'active' : '' }}">Contattami</a>
                    </li>
                    <li class="nav-item d-none d-lg-inline-block ms-lg-3"> {{-- Visibile da LG in su --}}
                        <a href="{{ route('contatti.form') }}"
                            class="btn btn-ctc {{ request()->routeIs('contatti.form') ? 'active' : '' }}">Contattami</a>
                    </li>

                    {{-- L'AUTENTICAZIONE UTENTE È STATA SPOSTATA FUORI DAL COLLAPSE --}}
                </ul>
            </div>
        </div>
    </nav>
</header>
