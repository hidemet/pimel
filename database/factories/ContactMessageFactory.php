<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use App\Models\Service; // Per selezionare un servizio di interesse
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContactMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $serviceOfInterest = null;
        // C'è una probabilità che il messaggio sia relativo a un servizio
        if ( fake()->boolean( 60 ) && Service::count() > 0 ) {
            // 60% di probabilità e se esistono servizi
            $service = Service::inRandomOrder()->where( 'is_active', true )
                ->first();
            if ( $service ) {
                $serviceOfInterest = $service->name;
                // Usiamo il nome del servizio come stringa
            }
        }

        return [
            'name'                => fake()->name(),
            'email'               => fake()->safeEmail(),
            'subject'             => fake()->optional()->sentence( rand( 3, 7 ) ),
                                                                    // Oggetto opzionale
            'message'             => fake()->paragraph( rand( 2, 6 ) ), // Messaggio
            'service_of_interest' => $serviceOfInterest,
            'is_read'             => fake()->boolean( 30 ),
            // 30% di probabilità che sia già stato letto
            'archived_at'         => fake()->optional( 0.1 )->dateTimeThisYear(),
            // 10% di probabilità di essere archiviato
            // created_at e updated_at sono gestiti automaticamente
        ];
    }

    /**
     * Indicate that the message is read.
     */
    public function read(): static
    {
        return $this->state( fn( array $attributes ) => [
            'is_read' => true,
        ] );
    }

    /**
     * Indicate that the message is unread.
     */
    public function unread(): static
    {
        return $this->state( fn( array $attributes ) => [
            'is_read' => false,
        ] );
    }

    /**
     * Indicate that the message is archived.
     */
    public function archived(): static
    {
        return $this->state( fn( array $attributes ) => [
            'archived_at' => now(),
        ] );
    }
}