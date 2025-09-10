
<header class="sticky-top">
  <nav class="navbar navbar-expand-md py-3 px-5 bg-body shadow-sm">
    {{-- 1. LOGO (a sinistra) --}}
    <a
      class="navbar-brand"
      href="{{ route('home') }}"
    >
      <img
        src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
        alt="PIMEL Logo"
        height="35"
      />
    </a>

    {{-- 2. TOGGLER (Bootstrap lo posiziona a destra automaticamente su mobile rispetto al brand) --}}
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNavContent"
      aria-controls="navbarNavContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- 3. CONTENUTO COLLASSABILE --}}
    <div
      class="collapse navbar-collapse"
      id="navbarNavContent"
    >
      {{-- Questa UL usa ms-auto per spingere TUTTO il suo contenuto (link, bottone, utente) a destra --}}
      <ul class="navbar-nav ms-auto align-items-center">
        {{-- Voci di menu --}}
        <li class="nav-item mx-lg-1">
          <a
            class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
            href="{{ route('home') }}"
          >
            Home
          </a>
        </li>
        <li class="nav-item mx-lg-1">
          <a
            class="nav-link {{ request()->routeIs('blog.index') || request()->routeIs('blog.show') ? 'active' : '' }}"
            href="{{ route('blog.index') }}"
          >
            Blog
          </a>
        </li>
        <li class="nav-item mx-lg-1">
          <a
            class="nav-link {{ request()->routeIs('servizi.index') ? 'active' : '' }}"
            href="{{ route('servizi.index') }}"
          >
            Servizi
          </a>
        </li>
        <li class="nav-item mx-lg-1">
          <a
            class="nav-link {{ request()->routeIs('pages.about') ? 'active' : '' }}"
            href="{{ route('pages.about') }}"
          >
            Chi Sono
          </a>
        </li>
        <li class="nav-item mx-lg-1">
          <a
            class="nav-link {{ request()->routeIs('newsletter.form') ? 'active' : '' }}"
            href="{{ route('newsletter.form') }}"
          >
            Newsletter
          </a>
        </li>

        {{-- Pulsante Contattami --}}
        {{-- mt-2 mt-md-0 per spaziatura su mobile, ms-md-2 per spazio da ultimo link su desktop --}}
        <li class="nav-item mx-lg-1 mt-2 mt-md-0 ms-md-2">
          <a
            href="{{ route('contatti.form') }}"
            class="btn btn-ctc {{ request()->routeIs('contatti.form') ? 'active' : '' }}"
          >
            Contattami
          </a>
        </li>

        {{-- Icona/Dropdown Utente --}}
        {{-- mt-2 mt-md-0 per spaziatura su mobile, ms-md-2 per spazio da Contattami su desktop --}}
        <li class="nav-item mt-2 mt-md-0 ms-md-2">
          @guest
            <a
              class="nav-link text-custom-navbar p-2"
              href="{{ route('login') }}"
              title="Accedi o Registrati"
            >
              <span class="material-symbols-outlined align-middle fs-4">
                person
              </span>
            </a>
          @else
            <div class="dropdown">
              <a
                class="nav-link dropdown-toggle d-flex align-items-center p-2"
                href="#"
                id="userDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="material-symbols-outlined fs-4 me-md-1">
                  account_circle
                </span>
                <span class="d-none d-md-inline-block small">
                  {{ Str::limit(Auth::user()->name, 15) }}
                </span>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end shadow-sm mt-2"
                aria-labelledby="userDropdown"
              >
                {{-- Dropdown items --}}
                <li class="px-3 py-2">
                  <div class="fw-bold">{{ Auth::user()->name }}</div>
                  <div class="small text-muted">{{ Auth::user()->email }}</div>
                </li>
                <li>
                  <hr class="dropdown-divider my-1" />
                </li>
                @if (Auth::user()->isAdmin())
                  <li>
                    <a
                      class="dropdown-item small"
                      href="{{ route('admin.dashboard') }}"
                    >
                      Pannello Admin
                    </a>
                  </li>
                @endif

                <li>
                  <a
                    class="dropdown-item small"
                    href="{{ route('profile.edit') }}"
                  >
                    Mio Profilo
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider my-1" />
                </li>
                <li>
                  <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="m-0"
                  >
                    @csrf
                    <button
                      type="submit"
                      class="dropdown-item small"
                    >
                      <span class="material-symbols-outlined align-middle me-2">
                        logout
                      </span>
                      Esci
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          @endguest
        </li>
      </ul>
    </div>
  </nav>
</header>
