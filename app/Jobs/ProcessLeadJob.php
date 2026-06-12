<?php
declare(strict_types=1);

namespace App\Jobs;
use App\Models\Lead;
use App\Services\LeadQualificationService;
use App\Exceptions\DuplicateLeadException;
use App\Services\LeadDuplicationService;
// lav 11+
// push task to a background queue, instead of executing instantly on the main web request
use Illuminate\Contracts\Queue\ShouldQueue; // async
use Illuminate\Database\QueryException;
// clean up codebase imports to helpp background configure delay(), onQueue(), and serializing
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\DB; // db failsafe
use Illuminate\Support\Facades\Log; // logging messages

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
    public function handle(
        LeadQualificationService $qualifier, 
        LeadDuplicationService $deduplicator): void
    {   
        try {
            // wrap in DB transaction for safety. (task 2)
            // if this block fails, DB rolls back to prevent incomplete or corrupt data records
            DB::transaction(function() use ($qualifier, $deduplicator){
                // call deduplicator (task 3)
                $deduplicator->checkDuplicate($this->leadData);

                $status = $qualifier->qualify($this->leadData);
                // triggers ORM to insert new row into the leads table
                Lead::create([
                    // spread optr to unpack keys and values in $this variable into creation array
                    // while appending or overriding "status" value
                    ...$this->leadData, 
                    'status' => $status,
                    // explicitly append the concat value, so it doesnt pass null value and fail the job
                    'unique_lead' => "{$this->leadData['email']}-{$this->leadData['phone_number']}-{$this->leadData['monthly_electricity_bill_rm']}"
                ]);
            });

        } catch (QueryException $e) {
            // this exception only works without background worker
            // PostgreSQL unique violation error (SQLSTATE 23505)
            if ($e->getCode() === '23505') {
                abort(422, 'Duplicate entry detected. Data Exist in DB');
            }
            throw $e; 
        }
    }

    // error handling jobs for queue
    public function failed(\Throwable $exception): void
    {
        // if found duplicate, fail the job without retries
        if ($exception instanceof DuplicateLeadException) {
            Log::info('Duplicate lead rejected', [
                'email' => $this->leadData['email'],
                'phone_number' => $this->leadData['phone_number'],
                'monthly_electricity_bill_rm' => $this->leadData['monthly_electricity_bill_rm'],
            ]);
            $this->fail($exception);
        }
    }

}
