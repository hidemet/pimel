@props([
  'title',
])

<header class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-7 text-center">
        <h1 class="h2 fw-semibold mb-2 mt-2">{{ $title }}</h1>
        {{ $slot }}
      </div>
    </div>
  </div>
</header>
