<?php
declare(strict_types=1);

namespace App\Jobs;
use App\Models\Lead;

// lav 11+
// push task to a background queue, instead of executing instantly on the main web request
use Illuminate\Contracts\Queue\ShouldQueue; // async
// clean up codebase imports to helpp background configure delay(), onQueue(), and serializing
use Illuminate\Foundation\Queue\Queueable;


class ProcessLeadJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 30;
    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $leadData
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        Lead::create($this->leadData);
    }
}
