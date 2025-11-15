<?php

namespace App\Events;

use App\Models\Card;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Card $card;
    public string $action;
    public ?string $description = null;
    public ?string $oldValue = null;
    public ?string $newValue = null;

    public function __construct(
        Card $card,
        string $action,
        ?string $description = null,
        ?string $oldValue = null,
        ?string $newValue = null
    ) {
        $this->card = $card;
        $this->action = $action;
        $this->description = $description;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
