<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LateSmsNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $parentNumber;
    public $studentName;
    /**
     * Create a new job instance.
     */
    public function __construct($parentNumber, $studentName)
    {
        $this->parentNumber = $parentNumber;
        $this->studentName = $studentName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $basic = new \Vonage\Client\Credentials\Basic("9af65d3f", "Ny92OinIz6PjfOnc");
        $client = new \Vonage\Client($basic);

        $client->sms()->send(
            new \Vonage\SMS\Message\SMS(
                "+" . $this->parentNumber,
                'AMS',
                'Hi Parent, Your child, ' . $this->studentName . ', has been late for their class today. Please remind them to log in earlier. Thank you!'
            )
        );
    }
}
