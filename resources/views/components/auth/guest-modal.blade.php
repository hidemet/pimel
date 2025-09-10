{{-- resources/views/components/auth/guest-modal.blade.php --}}
@props([
    'id' => 'guestActionModal',
    'title' => 'Azione Richiesta',
    'message' => 'Per eseguire questa azione, è necessario accedere o creare un account.',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center" id="{{ $id }}Label">
                {{ $title }}
            </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>{{ $message }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Accedi
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Registrati Ora</a>
            </div>
        </div>
    </div>
</div>
