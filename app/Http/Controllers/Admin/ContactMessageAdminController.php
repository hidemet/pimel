<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageAdminController extends Controller
{
    /**
     * Mostra la lista dei messaggi di contatto.
     */
    public function index(Request $request): View
    {
        // Query per recuperare i messaggi, ordinati dal più recente
        $query = ContactMessage::query()->orderBy('created_at', 'desc');

        $messages = $query->paginate(15);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Messaggi Ricevuti']
        ];

        return view('admin.contacts.index', compact('messages', 'breadcrumbs'));
    }
}
