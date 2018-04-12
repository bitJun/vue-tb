<?php

namespace App\Jobs;

use App\Services\Utils\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMsg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $tplId;
    protected $params;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile, $tplId, $params)
    {
        $this->mobile = $mobile;
        $this->tplId = $tplId;
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SmsService $smsService)
    {
        $smsService->sendSms($this->mobile, $this->tplId, $this->params);
    }
}
