<?php

namespace App\Listeners;

use App\Events\CardUpdated;
use App\Models\CardHistory;

class LogCardHistory
{
    public function __construct()
    {
    }

    public function handle(CardUpdated $event)
    {
        CardHistory::create([
            'card_id' => $event->card->id,
            'action' => $event->action,
            'description' => $event->description,
            'old_value' => $event->oldValue,
            'new_value' => $event->newValue,
        ]);
    }
}
