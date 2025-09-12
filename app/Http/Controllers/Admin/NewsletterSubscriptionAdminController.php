<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriptionAdminController extends Controller
{

    public function index(Request $request): View
    {
        $subscriptions = NewsletterSubscription::query()
            ->with('rubrics')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Iscritti Newsletter'],
        ];

        return view('admin.newsletter.index', compact('subscriptions', 'breadcrumbs'));
    }
}
