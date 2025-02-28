<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ActivateUserAfterEmailVerification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        // Update the user's status to active when their email is verified
        $user = $event->user;

        // Add logging for debugging
        Log::info('Email verified for user', [
            'user_id' => $user->id,
            'email' => $user->email,
            'old_status' => $user->status
        ]);

        $user->status = 'active';
        $user->save();

        Log::info('User status updated to active', [
            'user_id' => $user->id,
            'new_status' => $user->status
        ]);
    }
}
