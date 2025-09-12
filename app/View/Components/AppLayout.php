<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View as IlluminateView;

class AppLayout extends Component
{
    public function __construct(
    ) {
    }

    public function render(): IlluminateView
    {
        return view('layouts.app');
    }
}
