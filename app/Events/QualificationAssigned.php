<?php

namespace App\Events;

use App\Qualification;
use App\Qualification_user;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QualificationAssigned implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $qualification_user;
    public $authorizedby;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Qualification_user $qualification_user, $authorizedby)
    {
        $this->qualification_user = $qualification_user;
        $this->authorizedby = $authorizedby;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
