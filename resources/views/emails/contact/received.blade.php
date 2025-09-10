<x-mail::message>
    # Nuovo Messaggio Ricevuto dal Sito PIMEL

    Hai ricevuto un nuovo messaggio tramite il form di contatto.

    **Da:** {{ $messageData->name }}
    **Email Mittente:** [{{ $messageData->email }}](mailto:{{ $messageData->email }})
    @if ($messageData->service_of_interest)
        **Servizio di Interesse:** {{ $messageData->service_of_interest }}
    @endif
    @if ($messageData->subject)
        **Oggetto:** {{ $messageData->subject }}
    @endif

    ---

    **Messaggio:**
    <x-mail::panel>
        {{ nl2br(e($messageData->message)) }}
    </x-mail::panel>

    ---

    Puoi rispondere direttamente a questa email per contattare l'utente.

    Grazie,<br>
    {{ config('app.name') }}
</x-mail::message>
