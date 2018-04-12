<?php

namespace App\Jobs;

use App\Services\Moker\MokerCommService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CommissionOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $moker = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($moker)
    {
        $this->moker = $moker;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MokerCommService $mokerCommService)
    {
        $mokerCommService->handleInvitationComm($this->moker);
    }
}
