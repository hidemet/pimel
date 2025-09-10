<section>
    <p class="mb-3 text-muted small">
        {{ __('Una volta eliminato il tuo account, tutte le sue risorse e i dati verranno eliminati definitivamente. Prima di eliminare il tuo account, scarica tutti i dati o le informazioni che desideri conservare.') }}
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Elimina Account') }}
    </button>

    <!-- Modale di Conferma Eliminazione Utente -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel"
        aria-hidden="true" @if ($errors->userDeletion->isNotEmpty()) data-bs-show="true" @endif {{-- Mostra la modale se ci sono errori di validazione --}}>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-0"> {{-- p-0 per non avere padding doppio --}}
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                            {{ __('Sei sicuro di voler eliminare il tuo account?') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">
                            {{ __('Una volta eliminato il tuo account, tutte le sue risorse e i dati verranno eliminati definitivamente. Inserisci la tua password per confermare che desideri eliminare definitivamente il tuo account.') }}
                        </p>

                        <div class="mt-3">
                            <label for="delete_user_password"
                                class="form-label visually-hidden">{{ __('Password') }}</label>
                            <input id="delete_user_password" name="password" type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="{{ __('Password') }}">
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Annulla') }}</button>
                        <button type="submit" class="btn btn-danger ms-2">{{ __('Elimina Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        $(function() {
            // Seleziona la modale tramite il suo ID usando jQuery.
            const $confirmUserDeletionModal = $('#confirmUserDeletionModal');

            // Controlla se l'elemento modale esiste nel DOM prima di aggiungere event listener.
            if ($confirmUserDeletionModal.length) {

                // Aggiunge un listener per l'evento 'hidden.bs.modal'.
                // Questo evento viene scatenato da Bootstrap quando la modale finisce di nascondersi.
                $confirmUserDeletionModal.on('hidden.bs.modal', function() {
                    // La logica per prevenire la riapertura al refresh dopo una chiusura manuale
                    // è complessa e richiederebbe una gestione dello stato lato client.
                    // Per ora, il comportamento di default (la modale si riapre se ci sono errori) è mantenuto.
                });

                // Se la direttiva Blade rileva errori di validazione nel bag 'userDeletion',
                // usa il metodo .modal('show') del plugin jQuery di Bootstrap per mostrare la modale.
                @if ($errors->userDeletion->isNotEmpty())
                    $confirmUserDeletionModal.modal('show');
                @endif
            }
        });
    </script>
@endpush
