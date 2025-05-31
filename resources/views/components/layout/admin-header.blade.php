{{-- resources/views/components/layout/admin-header.blade.php --}}
@props([
    'navBgClass' => 'bg-dark navbar-dark', // Default a navbar scura
])

<header class="sticky-top">
    <nav class="navbar navbar-expand-lg {{ $navBgClass }} shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/img/pimel_logo_light_mode.svg') }}" alt="PIMEL Admin Logo" height="30"
                    class="me-2"> {{-- Logo per tema scuro --}}
                <span class="fw-semibold">PIMEL Admin</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarContent"
                aria-controls="adminNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}"
                            href="{{ route('admin.articles.index') }}">Articoli</a>
                    </li>
                    {{--
                    <li class="nav-item">
                        <a class="nav-link {{-- request()->routeIs('admin.rubrics.*') ? 'active' : '' --}}"
                    href="#">Rubriche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{-- request()->routeIs('admin.comments.*') ? 'active' : '' --}}" href="#">Commenti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{-- request()->routeIs('admin.services.*') ? 'active' : '' --}}" href="#">Servizi</a>
                    </li>
                    --}}
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank" title="Visualizza Sito Pubblico">
                            <span class="material-symbols-outlined align-middle">visibility</span>
                            <span class="d-none d-lg-inline ms-1">Visualizza Sito</span>
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                id="adminUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined me-1">account_circle</span>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2" aria-labelledby="adminUserDropdown">
                                <li>
                                    <a class="dropdown-item small" href="{{ route('profile.edit') }}">
                                        <span
                                            class="material-symbols-outlined align-middle me-2 fs-6">manage_accounts</span>Mio
                                        Profilo
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item small text-danger">
                                            <span class="material-symbols-outlined align-middle me-2 fs-6">logout</span>Esci
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
