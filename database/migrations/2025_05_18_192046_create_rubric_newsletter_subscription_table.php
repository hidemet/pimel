<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rubric_newsletter_subscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('newsletter_subscription_id');
            $table->unsignedBigInteger('rubric_id');
            $table->timestamps();

            // Uso dei Foreign keys con nomi più corti per evitare l'errore sulla lunghezza
            $table->foreign('newsletter_subscription_id', 'fk_newsletter_subscription')
                ->references('id')->on('newsletter_subscriptions')->cascadeOnDelete();
            $table->foreign('rubric_id', 'fk_rubric')
                ->references('id')->on('rubrics')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_newsletter_subscription');
    }
};
