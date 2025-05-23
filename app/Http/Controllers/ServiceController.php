<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        $allActiveServices = Service::where('is_active', true)->get();


        $servicesByTarget = [
            'genitori' => $services->filter(function ($service) {
                // Adapta questa logica al tuo campo effettivo
                // Esempio: se hai un campo 'target_audience_slug' o 'category'
                // return $service->target_audience_slug === 'genitori';
                // O se il nome contiene certe parole chiave (meno robusto)
                return stripos($service->name, 'Genitor') !== false || stripos($service->target_audience, 'Genitor') !== false;
            }),
            'professionisti' => $services->filter(function ($service) {
                // return $service->target_audience_slug === 'professionisti';
                return stripos($service->name, 'Educator') !== false ||
                       stripos($service->name, 'Insegnant') !== false ||
                       stripos($service->name, 'Supervisione') !== false && stripos($service->name, 'Scolastico') === false || // Evita Supervisione Scolastica qui
                       stripos($service->target_audience, 'Educator') !== false ||
                       stripos($service->target_audience, 'Insegnant') !== false;
            }),
            'scuole' => $services->filter(function ($service) {
                // return $service->target_audience_slug === 'scuole';
                return stripos($service->name, 'Scolastic') !== false ||
                       stripos($service->target_audience, 'Scuol') !== false ||
                       stripos($service->name, 'Studenti') !== false && stripos($service->name, 'Universitari') === false; // Supporto allo studio per scuole
            }),
        ];

        // Per gestire servizi che potrebbero non rientrare nettamente o per evitare duplicati
        // Questa è una logica di esempio, potresti aver bisogno di un sistema più robusto
        $assignedServiceIds = collect($servicesByTarget)->flatMap(function ($group) {
            return $group->pluck('id');
        })->unique();

        $servicesByTarget['altri'] = $services->reject(function ($service) use ($assignedServiceIds) {
            return $assignedServiceIds->contains($service->id);
        });

        // Rimuovi la categoria 'altri' se è vuota
        if ($servicesByTarget['altri']->isEmpty()) {
            unset($servicesByTarget['altri']);
        }


        return view('servizi.index', [
        'servicesByTarget' => $servicesByTarget,
        'allActiveServices' => $allActiveServices // Aggiungi questo
    ]);
    }
}