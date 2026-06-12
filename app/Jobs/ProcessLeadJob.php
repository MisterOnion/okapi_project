<?php
declare(strict_types=1);

namespace App\Jobs;
use App\Models\Lead;
use App\Services\LeadQualificationService;

// lav 11+
// push task to a background queue, instead of executing instantly on the main web request
use Illuminate\Contracts\Queue\ShouldQueue; // async
// clean up codebase imports to helpp background configure delay(), onQueue(), and serializing
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\DB;

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

    // plain direct insert example
    // public function handle(): void
    // {   
    //     Lead::create($this->leadData); 
    // }

    // create data based on Lead model
    // call LeadQualificationService during data ingestion, returns nothing
    public function handle(LeadQualificationService $qualifier): void
    {
        // wrap in DB transaction for safety. 
        // if this block fails, DB rolls back to prevent incomplete or corrupt data records
        DB::transaction(function() use ($qualifier){
            $status = $qualifier->qualify($this->leadData);
            // triggers ORM to insert new row into the leads table
            Lead::create([
                // spread optr to unpack keys and values in $this variable into creation array
                // while appending or overriding "status" value
                ...$this->leadData, 
                'status' => $status]);
        });
    }

}
