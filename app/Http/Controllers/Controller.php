<?php

namespace App\Http\Controllers;

// Rimuovi o commenta qualsiasi 'use App\Http\Controllers\Controller;' se presente qui
// Assicurati che ci sia questo use statement per la classe base di Laravel:
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Importa la classe base di Laravel

class Controller extends BaseController
// Assicurati che estenda BaseController (o il namespace completo)
{
    use AuthorizesRequests, ValidatesRequests;
}