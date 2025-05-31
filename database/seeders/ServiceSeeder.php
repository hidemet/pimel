<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\TargetCategory; // Importa TargetCategory
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB; // Per il truncate, se necessario

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Service::truncate(); // Opzionale
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Se usi MySQL e hai problemi con truncate e FK
        // Service::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Recupera le categorie per associarle
        $catGenitori = TargetCategory::where('slug', 'genitori')->first();
        $catProfessionisti = TargetCategory::where('slug', 'professionisti')->first();
        $catScuole = TargetCategory::where('slug', 'scuole')->first();
        $catStudenti = TargetCategory::where('slug', 'studenti')->first();
        $catAltri = TargetCategory::where('slug', 'altri-servizi')->first();

        // Se una categoria non esiste, potresti volerla creare o lanciare un errore
        if (!$catGenitori || !$catProfessionisti || !$catScuole || !$catStudenti || !$catAltri) {
            $this->command->error('Una o più categorie target non trovate. Esegui TargetCategorySeeder prima.');
            // Potresti chiamare il seeder qui:
            // $this->call(TargetCategorySeeder::class);
            // E poi ricaricare le categorie.
            return;
        }

        $servicesData = [
            [
                'name' => 'Consulenza Pedagogica Genitoriale 0-6 anni',
                'target_category_id' => $catGenitori->id,
                // ... (gli altri campi come prima: description, target_audience, objectives, etc.)
                'description'     => "Un supporto mirato per affrontare le piccole e grandi sfide della crescita dei bambini nella fascia d'età prescolare, dalla gestione delle routine al sostegno nello sviluppo delle autonomie e della regolazione emotiva.",
                'target_audience' => "Genitori di bambini da 0 a 6 anni, coppie in attesa, nonni e altre figure educative di riferimento primarie.",
                'objectives'      => "- Sviluppare una comprensione specifica delle tappe evolutive del proprio bambino (0-6 anni) e delle sue esigenze individuali, identificando almeno 2-3 aree di focus entro i primi incontri.\n- Acquisire e applicare almeno 3 nuove strategie comunicative efficaci per ridurre i conflitti e migliorare l'interazione quotidiana con il bambino, monitorandone l'efficacia nel corso di 4 settimane.\n- Implementare cambiamenti concreti nell'ambiente domestico e nelle routine per creare un contesto più sereno e stimolante, con un piano d'azione definito e verificabile entro 2 mesi.\n- Ridurre la frequenza e/o l'intensità dei momenti critici (es. sonno, pasti, gestione dei capricci) del 20-30% (o un altro indicatore qualitativo osservabile) applicando tecniche concordate per un periodo di 6 settimane.\n- Identificare e promuovere almeno 2-3 nuove autonomie adeguate all'età del bambino (es. vestirsi da solo, riordinare i giochi) attraverso un piano di supporto graduale, raggiungendo l'obiettivo entro un periodo concordato (es. 1-2 mesi).",
                'modalities'      => "Colloqui individuali o di coppia (online via Google Meet/Zoom o in presenza), percorsi personalizzati di 5-10 incontri, workshop tematici di gruppo (durata 2-3 ore), incontri di parent-coaching focalizzati.",
                'is_active'       => true,
            ],
            [
                'name' => 'Supporto Pedagogico per Genitori di Preadolescenti e Adolescenti',
                'target_category_id' => $catGenitori->id,
                'description'     => "Un percorso per aiutare i genitori a comprendere e navigare le complessità della preadolescenza e adolescenza, migliorando la comunicazione e la relazione con i propri figli in questa fase di grandi trasformazioni.",
                'target_audience' => "Genitori di ragazzi/e dagli 11 ai 18 anni.",
                'objectives'      => "- Acquisire una conoscenza approfondita dei principali cambiamenti (psicofisici, emotivi, sociali) specifici della fase adolescenziale del proprio figlio, partecipando attivamente a sessioni informative e di discussione nel corso del primo mese di consulenza.\n- Migliorare significativamente la qualità del dialogo con il proprio figlio adolescente, implementando almeno 2 tecniche di ascolto attivo e comunicazione assertiva, con l'obiettivo di aumentare le interazioni positive settimanali (monitorabile tramite diario o autovalutazione) entro 8 settimane.\n- Sviluppare e applicare un piano condiviso per la gestione dei conflitti e la negoziazione delle regole, riducendo gli scontri familiari su almeno 1-2 aree problematiche identificate, monitorando i progressi ogni due settimane per un periodo di 2 mesi.\n- Fornire un supporto più efficace al figlio nelle sue scelte scolastiche e personali, definendo insieme un percorso di esplorazione delle opzioni e supporto decisionale da completare entro un semestre.\n- Implementare strategie familiari mirate a promuovere l'autonomia responsabile e il benessere psicologico del figlio, con un focus su 2 obiettivi specifici (es. gestione del tempo, responsabilità domestiche) da raggiungere progressivamente in 3 mesi.",
                'modalities'      => "Consulenze individuali o di coppia per genitori (pacchetti da 3, 5 o 10 sessioni), percorsi di gruppo tematici per genitori (cicli di 4-6 incontri), mediazione familiare su specifici conflitti.",
                'is_active'       => true,
            ],
            [
                'name' => 'Orientamento e Supporto per Genitori con Figli con DSA/BES',
                'target_category_id' => $catGenitori->id,
                'description'     => "Consulenza specializzata per genitori di bambini e ragazzi con Disturbi Specifici dell'Apprendimento (DSA) o Bisogni Educativi Speciali (BES), per comprendere la diagnosi, individuare strategie efficaci e collaborare con la scuola.",
                'target_audience' => "Genitori di bambini e ragazzi con diagnosi o sospetto di DSA/BES, dalla scuola primaria alla secondaria di secondo grado.",
                'objectives'      => "- Fornire una chiara comprensione della diagnosi specifica del figlio e delle sue implicazioni pratiche entro 2 sessioni.\n- Identificare e selezionare almeno 3 strategie di studio personalizzate e 2 strumenti compensativi/dispensativi efficaci, da sperimentare e valutare nell'arco di un mese.\n- Elaborare o revisionare in modo collaborativo il Piano Didattico Personalizzato (PDP), assicurando che contenga obiettivi misurabili e strategie concrete, in tempo per la scadenza scolastica.\n- Migliorare la comunicazione con la scuola, partecipando attivamente ad almeno un incontro GLO/team docenti con una preparazione specifica fornita durante la consulenza.\n- Incrementare l'autostima e la motivazione allo studio del figlio, attraverso l'applicazione di tecniche di rinforzo positivo e la creazione di un ambiente di apprendimento supportivo a casa, con progressi osservabili in 2-3 mesi.",
                'modalities'      => "Colloqui individuali di consulenza (online o in presenza), analisi della documentazione diagnostica e scolastica, supporto nella preparazione agli incontri scolastici, incontri informativi e formativi per piccoli gruppi di genitori.",
                'is_active'       => true,
            ],
            [
                'name' => 'Formazione Pedagogica Continua per Educatori e Insegnanti',
                'target_category_id' => $catProfessionisti->id,
                'description'     => "Percorsi formativi personalizzati e workshop su tematiche pedagogiche attuali, metodologie didattiche innovative, gestione del gruppo classe, inclusione e benessere emotivo in contesti educativi.",
                'target_audience' => "Educatori di nidi e scuole dell'infanzia, insegnanti di ogni ordine e grado, coordinatori pedagogici, staff di servizi educativi.",
                'objectives'      => "- Acquisire e dimostrare padronanza di almeno 2-3 nuove competenze pedagogiche o metodologie didattiche specifiche (es. debate, cooperative learning, outdoor education) al termine del percorso formativo di 25 ore, attraverso la progettazione e presentazione di un'unità didattica applicativa.\n- Implementare con successo almeno una nuova strategia di gestione del gruppo classe o di promozione di dinamiche relazionali positive, monitorandone gli effetti sul clima di classe e sul comportamento degli studenti per almeno un mese post-formazione, documentando i risultati.\n- Sviluppare un piano d'azione concreto e attuabile per promuovere l'inclusione di studenti con BES o background diversi all'interno della propria pratica didattica quotidiana, da presentare e discutere al termine del corso di 10 ore.\n- Produrre un elaborato di riflessione critica (min. 3 pagine) sulla propria pratica educativa, basato sui contenuti del corso, identificando almeno 2 aree di miglioramento personale e professionale e un piano per affrontarle nei successivi 6 mesi.\n- Partecipare attivamente ad almeno l'80% delle sessioni di confronto e discussione previste dal programma formativo, contribuendo con esperienze e feedback costruttivi.",
                'modalities'      => "Workshop intensivi (1-2 giorni), corsi di formazione strutturati (es. 20-50 ore distribuite su più settimane), webinar tematici interattivi (90-120 minuti), supervisione pedagogica individuale o di piccolo gruppo (cicli di 3-5 incontri).",
                'is_active'       => true,
            ],
            [
                'name' => 'Supervisione Pedagogica per Team Educativi',
                'target_category_id' => $catProfessionisti->id, // O potrebbe essere $catScuole se i team sono sempre scolastici
                'description'     => "Uno spazio di riflessione guidata e supporto per equipe di lavoro in ambito educativo (nidi, scuole, comunità), finalizzato a migliorare la coesione del team, analizzare casi, affrontare criticità e promuovere pratiche educative efficaci e condivise.",
                'target_audience' => "Team di educatori, insegnanti, operatori di servizi per l'infanzia e l'adolescenza, coordinatori di strutture educative.",
                'objectives'      => "- Analizzare e migliorare le dinamiche comunicative e collaborative all'interno del team, identificando almeno 2 aree di forza e 2 aree di miglioramento su cui lavorare collettivamente nel corso di 3 sessioni.\n- Elaborare strategie di intervento condivise ed efficaci per almeno 2-3 casi educativi complessi presentati dal team, definendo ruoli e azioni specifiche per ciascun membro entro la fine del ciclo di supervisione di 5 incontri.\n- Ridurre il livello di stress percepito e migliorare il senso di appartenenza al team, misurato tramite questionari anonimi pre e post ciclo di supervisione (obiettivo: miglioramento del 15%).\n- Definire un protocollo operativo condiviso per la gestione di una specifica criticità ricorrente (es. inserimento, gestione dei conflitti tra bambini) entro la fine del percorso.\n- Aumentare la coerenza delle pratiche educative all'interno del servizio, verificata tramite osservazioni o checklist condivise su almeno 3 aspetti chiave.",
                'modalities'      => "Incontri periodici di gruppo (es. mensili o bimestrali, durata 2-3 ore, in presenza o online), analisi di casi studio portati dal team, utilizzo di tecniche di problem solving collaborativo, focus group facilitati, attività di team building pedagogico.",
                'is_active'       => true,
            ],
            [
                'name' => 'Supporto allo Studio e Orientamento per Studenti (Secondaria)',
                'target_category_id' => $catStudenti->id,
                'description'     => "Percorsi individualizzati per aiutare gli studenti della scuola secondaria a sviluppare un metodo di studio efficace, migliorare l'organizzazione, gestire l'ansia da prestazione e fare scelte consapevoli per il proprio futuro formativo.",
                'target_audience' => "Studenti della scuola secondaria di primo e secondo grado (11-18 anni).",
                'objectives'      => "- Identificare lo stile di apprendimento prevalente dello studente e sviluppare un piano di studio personalizzato che includa almeno 3 tecniche specifiche (es. mappe, schemi, flashcard) entro 4 sessioni.\n- Migliorare l'organizzazione del materiale di studio e la pianificazione settimanale delle attività, con l'obiettivo di completare i compiti assegnati rispettando le scadenze per l'80% delle volte, monitorato per un mese.\n- Ridurre i sintomi percepiti di ansia da prestazione (valutati con scala soggettiva) prima di verifiche o interrogazioni, applicando almeno 2 tecniche di gestione dello stress apprese, con un miglioramento percepito entro 6 settimane.\n- Incrementare la media scolastica in almeno 1-2 materie target di 0.5-1 punto entro la fine del quadrimestre/semestre, come risultato dell'applicazione del nuovo metodo di studio.\n- Elaborare un piano di orientamento personalizzato per la scelta della scuola superiore o del percorso post-diploma, identificando almeno 2-3 opzioni realistiche e motivanti entro la fine del percorso di 5 incontri.",
                'modalities'      => "Colloqui individuali (online o in presenza, pacchetti da 5 o 10 incontri), laboratori di gruppo sul metodo di studio (cicli di 4-6 incontri), somministrazione (se abilitati) e discussione di test attitudinali e di interesse, percorsi di coaching allo studio.",
                'is_active'       => true,
            ],
             [
                'name' => 'Tutoring Pedagogico per Studenti Universitari (Area Educativa)',
                'target_category_id' => $catStudenti->id, // O $catProfessionisti se sono futuri professionisti
                'description'     => "Supporto personalizzato per studenti universitari dei corsi di laurea in Scienze dell'Educazione e Scienze Pedagogiche, per approfondire tematiche specifiche, preparare esami, sviluppare progetti o tesi.",
                'target_audience' => "Studenti universitari iscritti a corsi di laurea in Scienze dell'Educazione, Scienze Pedagogiche, Formazione Primaria.",
                'objectives'      => "- Migliorare la comprensione e l'esposizione di almeno 2-3 concetti teorici pedagogici complessi necessari per un esame specifico, verificato tramite simulazione orale o scritta al termine del ciclo di tutoring.\n- Strutturare e redigere una sezione specifica (es. introduzione, capitolo teorico) della tesi di laurea, rispettando i criteri accademici e completandola entro una scadenza concordata (es. 3 settimane).\n- Superare un esame universitario target con un voto minimo di 24/30, grazie al supporto focalizzato ricevuto durante 5-8 sessioni di tutoring.\n- Sviluppare un project work o un elaborato richiesto dal corso di studi, completo in tutte le sue parti e qualitativamente adeguato, entro la deadline accademica.\n- Acquisire maggiore sicurezza nella presentazione orale di contenuti pedagogici, attraverso esercitazioni pratiche e feedback mirati durante le sessioni.",
                'modalities'      => "Lezioni individuali o in piccoli gruppi (max 3 persone) online, revisione critica di bozze di elaborati/tesi, consulenza bibliografica e metodologica, simulazioni d'esame guidate, supporto nella strutturazione di presentazioni.",
                'is_active'       => false,
            ],
            [
                'name' => 'Progettazione e Realizzazione di Interventi Educativi Scolastici',
                'target_category_id' => $catScuole->id,
                'description'     => "Consulenza e supporto alle scuole per la progettazione, implementazione e valutazione di progetti educativi su tematiche specifiche (es. educazione emotiva, prevenzione del bullismo, cittadinanza digitale, educazione all'affettività).",
                'target_audience' => "Scuole di ogni ordine e grado (infanzia, primaria, secondaria), istituzioni educative, enti del terzo settore che collaborano con le scuole.",
                'objectives'      => "- Completare un'analisi dettagliata dei bisogni formativi del contesto scolastico specifico (identificando almeno 3 aree prioritarie e stakeholder chiave) entro le prime 2-3 settimane di consulenza, attraverso interviste, focus group e analisi documentale.\n- Co-progettare un intervento educativo completo e dettagliato (comprensivo di obiettivi SMART specifici per gli studenti, attività, cronoprogramma dettagliato per un anno scolastico e indicatori di valutazione misurabili) sulla tematica concordata (es. educazione emotiva) entro 4-6 settimane dalla definizione dei bisogni.\n- Realizzare con successo il 100% dei laboratori/attività previsti con gli studenti, rispettando il cronoprogramma e raggiungendo gli obiettivi di partecipazione (es. almeno 80% degli studenti target) e gradimento (es. punteggio medio di 4/5 in questionari di feedback).\n- Formare almeno il 90% del personale docente direttamente coinvolto nel progetto sulle metodologie e i contenuti specifici, verificando l'apprendimento tramite project work applicativi o simulazioni pratiche, entro la conclusione del progetto.\n- Produrre un report finale di monitoraggio e valutazione dell'intervento, basato su indicatori quantitativi (es. riduzione episodi di bullismo del 10%) e qualitativi (es. focus group con studenti e docenti), che evidenzi i risultati raggiunti e le aree di miglioramento, entro 2 settimane dal termine delle attività.",
                'modalities'      => "Conduzione di analisi dei bisogni (survey, interviste), sessioni di co-progettazione partecipata con il team scolastico, conduzione diretta di laboratori con studenti, erogazione di moduli formativi per docenti, monitoraggio in itinere e valutazione finale, stesura di reportistica dettagliata.",
                'is_active'       => true,
            ],
            [
                'name' => "Sportello d'Ascolto Pedagogico Scolastico",
                'target_category_id' => $catScuole->id, // Potrebbe coinvolgere anche $catStudenti e $catGenitori, ma il servizio è "per la scuola"
                'description'     => "Attivazione di uno sportello d'ascolto pedagogico all'interno dell'istituto scolastico, rivolto a studenti, genitori e docenti, per offrire uno spazio di confronto, supporto e orientamento su problematiche educative, relazionali e di apprendimento.",
                'target_audience' => "Studenti (principalmente scuola secondaria), genitori, docenti e personale ATA di un istituto scolastico.",
                'objectives'      => "- Fornire un minimo di 8 ore settimanali di ascolto qualificato e non giudicante accessibili su appuntamento, garantendo la presa in carico di almeno l'80% delle richieste entro 5 giorni lavorativi per tutta la durata dell'anno scolastico.\n- Realizzare almeno 3 interventi di consulenza breve (max 3 incontri) per ciascuna problematica educativa specifica presentata, finalizzati all'individuazione di strategie risolutive immediate o all'orientamento verso risorse specialistiche.\n- Facilitare la risoluzione di almeno il 50% delle difficoltà comunicative segnalate tra le diverse componenti della comunità scolastica (es. studente-docente, genitore-docente) attraverso interventi di mediazione o consulenza mirata.\n- Orientare correttamente il 100% degli utenti che necessitano di supporto specialistico esterno verso le risorse territoriali adeguate (ASL, consultori, specialisti privati) entro 2 incontri.\n- Organizzare e condurre almeno 2 incontri tematici di gruppo (es. su cyberbullismo, gestione delle emozioni) per studenti o genitori durante l'anno scolastico, con una partecipazione minima di 15 persone per incontro.",
                'modalities'      => "Colloqui individuali su appuntamento (in presenza all'interno di uno spazio dedicato nella scuola o online in casi specifici), incontri tematici di gruppo per studenti, genitori o docenti, collaborazione con le figure di sistema della scuola (referenti BES/DSA, psicologo scolastico se presente), reportistica periodica anonima sull'andamento del servizio alla dirigenza.",
                'is_active'       => true,
            ],
            // Aggiungi qui altri servizi specifici, assegnando la target_category_id corretta
            // Se un servizio non rientra chiaramente, usa $catAltri->id o null se hai reso la colonna nullable
            // e non hai una categoria "Altri Servizi" predefinita.
        ];

        foreach ($servicesData as $serviceDetails) {
            Service::create($serviceDetails);
        }

        $this->command->info(count($servicesData) . ' servizi specifici creati/aggiornati.');

        // Opzionale: Crea alcuni servizi aggiuntivi con la factory, se vuoi più dati
        // Assegna loro una categoria casuale o una specifica se usi lo state della factory.
        // Service::factory()->count(5)->create(); // La factory assegnerà categorie casuali o null
    }
}