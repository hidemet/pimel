<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use App\Models\Service;
use Illuminate\View\View;

class PageController extends Controller {
    public function about(): View {
        return view('chi-sono');
    }

    public function contactForm(): View {
        $services = Service::orderBy('name')->pluck('name', 'name');

        return view('contatti.form', compact('services'));
    }

    public function newsletterForm(): View {
        $rubrics = Rubric::orderBy('name')->get();

        return view('newsletter.form', compact('rubrics'));
    }
}
