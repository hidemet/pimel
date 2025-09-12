<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard'],
        ];

        return view('admin.dashboard', compact('breadcrumbs'));
    }
}
