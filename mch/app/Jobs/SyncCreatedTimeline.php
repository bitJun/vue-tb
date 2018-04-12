<?php

namespace App\Jobs;

use App\Services\Timeline\TimelineService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncCreatedTimeline implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeline;
    protected $count;
    protected $limit;
    protected $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($timeline,$count,$offset,$limit)
    {
        $this->timeline = $timeline;
        $this->count = $count;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TimelineService $timelineService)
    {
        $timelineService->syncCreatedTimelineToMember($this->timeline,$this->count,$this->offset,$this->limit);
    }
}
