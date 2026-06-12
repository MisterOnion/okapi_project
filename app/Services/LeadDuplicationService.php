<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\DuplicateLeadException;
use App\Models\Lead;

class LeadDuplicationService
{
    public function checkDuplicate(array $leadData): void
    {
        $exists = Lead::where('email', $leadData['email'])
            ->where('phone_number', $leadData['phone_number'])
            ->where('monthly_electricity_bill_rm', $leadData['monthly_electricity_bill_rm'])
            ->exists();

        if ($exists) {
            throw new DuplicateLeadException();
        }
    }
}